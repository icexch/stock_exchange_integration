<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

use Warchiefs\StockExchangeIntegration\Contracts\StockExchange as Exchange;
use GuzzleHttp\Client;
use App\RollingCurlX;

/**
 * Parent class for StockExchange
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
abstract class StockExchange implements Exchange
{

	protected $api_uri;
	protected $client;

    /*Currency return instead USD*/
	protected $fiat = null;
	protected $onlyFiat = false;

	protected static $prices;

	public $fiatsExchanges = [
	    'bithumb', 'btc38', 'coinone', 'huobi', 'korbit', 'okcoincn', 'zaif',
    ];

	/**
	 * Counstruct an url for api request
	 *
	 * @param       $method
	 * @param array $params
	 *
	 * @return string
	 */
	public function uri_construct($method, array $params = [])
	{
		if($params != []) {
			$params = http_build_query($params);
			return $this->api_uri.'/'.$method.'?'.$params;
		} else {
			return $this->api_uri.'/'.$method;
		}
	}

	/**
	 * Send api request
	 *
	 * @param       $method
	 * @param array $params
	 *
	 * @return null|string
	 */
	public function api_request($method, array $params = [])
	{
		$uri = $this->uri_construct($method, $params);
        $client = new Client();
        try {
            $request = $client->request('GET', $uri, [
                'http_errors' => false,
                'timeout' => 6,
            ]);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return null;
        }

        $response = $request->getBody()->getContents();

		return $response;
	}

    /**
     * @param $method
     * @param array $params
     * @return Promise\PromiseInterface
     */
    public function api_request_async($method, array $params = [])
    {
        $uri = $this->uri_construct($method, $params);
        $client = new Client();
        $promise = $client->requestAsync('GET', $uri, [
            'http_errors' => false,
            'timeout' => 3,
        ]);

        return $promise;
    }

    /**
     * Get array of prices
     *
     * @param string $first_currency
     * @param string $second_currency
     * @param null|callable $convertCallback
     * @param null|array $only - for return info only from this exchanges
     * @return array
     */
    public static function getAllPrices($first_currency = 'BTC', $second_currency = 'USD', $convertCallback = null, $only = null)
    {
        $containers = self::getExchangeContainers();

        $prices = [];

        foreach ($containers as $exchangeName => $container) {
            if (is_array($only)) {
                if (!in_array($exchangeName, $only)) {
                    continue;
                }
            }
            if ($container->isOnlyFiat() && $second_currency !== 'USD') {
                continue;
            }

            $price = $container->getPairPrice($first_currency, $second_currency);
            if ($second_currency === 'USD' && $fiatCurrency = $container->isFiat()) {
                if ($convertCallback) {
                    $price = $convertCallback($fiatCurrency, $price);
                } else {
                    continue;
                }
            }
            if ($price) {
                $prices[$exchangeName] = $price;
            }
        }

        return $prices;
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @param null $convertCallback
     * @param null $only
     * @return mixed
     */
    public function getAllPricesAsync($first_currency = 'BTC', $second_currency = 'USD', $convertCallback = null, $only = null)
    {
        $containers = self::getExchangeContainers($only);

        $rcx = new RollingCurlX(20);
        $rcx->setTimeout(8000);
        $options = [CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36"];
        foreach ($containers as $exchangeName => $container) {
            if (is_array($only)) {
                if (!in_array($exchangeName, $only)) {
                    continue;
                }
            }
            if ($container->isOnlyFiat() && $second_currency !== 'USD') {
                continue;
            }
            $requestUrl = $container->getPairPriceUrl($first_currency, $second_currency);
            if (is_array($requestUrl)) {
                $url = $container->uri_construct($requestUrl['uri'], $requestUrl['params']);
            } else {
                $url = $container->uri_construct($requestUrl);
            }

            $rcx->addRequest(
                $url,
                null,
                function($response, $url, $request_info, $user_data, $time) use ($container, $first_currency, $second_currency, $convertCallback, $exchangeName) {
                    $price = $container->getPairPriceHandle($response, $first_currency, $second_currency);
                    if ($price && $second_currency === 'USD' && $fiatCurrency = $container->isFiat()) {
                        if (is_callable($convertCallback)) {
                            $price = $convertCallback($fiatCurrency, $price);
                        }
                    }

                    if ($price) {
                        self::$prices[$exchangeName] = $price;
                    }
                },
                null,
                $options,
                null
            );
        }

        $rcx->execute();

        $prices = self::$prices;
        self::$prices = [];

        return $prices;
    }

    /**
     * Return avg of pair from all stock exchanges
     *
     * @param string $first_currency
     * @param string $second_currency
     * @param null|callable $convertCallback
     * @param null|array $only
     * @return null|float
     */
    public static function getTickerAverage($first_currency = 'BTC', $second_currency = 'USD', $convertCallback = null, $only = null)
    {
        $prices = self::getAllPrices($first_currency, $second_currency, $convertCallback, $only);

        if (count($prices) === 0) {
            return null;
        }

        return round(array_sum($prices) / count($prices), 8);
    }

    /**
     * Get available coins on all exchanges
     *
     * @return array
     */
    public function getAllAvailableCoins()
    {
        $containers = $this->getExchangeContainers();

        $coinsAll = [];

        foreach ($containers as $container) {
            if ($coins = $container->getAvailableCoins()) {
                foreach ($coins as $coin) {
                    if (!in_array($coin, $coinsAll)) {
                        $coinsAll[] = $coin;
                    }
                }
            }
        }

        return $coinsAll;
    }

    /**
     * Get array of exchange containers
     *
     * @return array
     */
    protected static function getExchangeContainers($only = null)
    {
        if (!($availableStocks = config('exchange.available'))) {
            $config = require_once('../Config/exchange.php');
            $availableStocks = $config['available'];
        }

        $containers = [];

        foreach ($availableStocks as $stock) {
            if (is_array($only)) {
                if (!in_array($stock, $only)) {
                    continue;
                }
            }
            $class = __NAMESPACE__  . '\\' . ucfirst($stock);
            if (!class_exists($class)) {
                continue;
            }
            $containers[$stock] = new $class;
        }

        return $containers;
    }

    /**
     * Get pair price
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return float|null
     */
    public function getPairPrice($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $url = $this->getPairPriceUrl($first_currency, $second_currency);

        if (is_array($url)) {
            $response = $this->api_request($url['uri'], $url['params']);
        } else {
            $response = $this->api_request($url);
        }

        return $this->getPairPriceHandle($response, $first_currency, $second_currency);
    }

    /**
     * If exchange change only fiat currency to cryptocurrency
     *
     * @return bool
     */
    public function isOnlyFiat()
    {
        return $this->onlyFiat;
    }

    /**
     * If exchange don`t have dollars get fiat currency
     *
     * @return null|string
     */
    public function isFiat()
    {
        return $this->fiat;
    }
}