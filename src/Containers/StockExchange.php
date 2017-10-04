<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

use Warchiefs\StockExchangeIntegration\Contracts\StockExchange as Exchange;
use GuzzleHttp\Client;

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

		try {
			$client = new Client();
			$request = $client->request('GET', $uri, ['http_errors' => false]);
			$response = $request->getBody()->getContents();
		} catch (\Exception $e) {
			$response = false;
		}


		return $response;
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
    protected static function getExchangeContainers()
    {
        if (!($availableStocks = config('exchange.available'))) {
            $config = require_once('../Config/exchange.php');
            $availableStocks = $config['available'];
        }

        $containers = [];

        foreach ($availableStocks as $stock) {
            $class = __NAMESPACE__  . '\\' . ucfirst($stock);
            if (!class_exists($class)) {
                continue;
            }
            $containers[$stock] = new $class;
        }

        return $containers;
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
