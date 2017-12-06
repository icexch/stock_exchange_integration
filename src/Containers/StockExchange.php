<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

use Warchiefs\StockExchangeIntegration\Contracts\StockExchange as Exchange;
use GuzzleHttp\Client;
use marcushat\RollingCurlX;

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
	protected static $buffer;
	protected $userAgent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36";

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
     * @param array $data - see data format in config
     * @param null|Callable $convertFiatCallback
     * @param null|Callable $convertCryptoCallback
     * @param array $exchangesAllPrices - exchanges where we can get all prices from one request
     * @param int $timeout
     * @param int $concurrent_requests
     * @return array
     */
    public function getAllPrices($data, $convertFiatCallback = null, $convertCryptoCallback = null, $exchangesAllPrices = [], $timeout = 10000, $concurrent_requests = 30)
    {
        $containers = self::getExchangeContainers(array_keys($data));
        $rcx = new RollingCurlX($concurrent_requests);
        $rcx->setTimeout($timeout);
        $options = [CURLOPT_USERAGENT => $this->userAgent];
        foreach ($data as $exchangeName => $pairs) {
            $container = $containers[$exchangeName];
            if (in_array($exchangeName, $exchangesAllPrices, true)) {
                if ($container->needV2()) {
                    $container->changeApiVersionTo2();
                }
                $requestUrl = $container->getAllPairsPricesUrl($pairs);
                $url = $container->uri_construct($requestUrl['uri'], $requestUrl['params']);
                $rcx->addRequest(
                    $url,
                    null,
                    function($response, $url, $request_info, $user_data, $time) use ($container, $pairs, $convertFiatCallback, $convertCryptoCallback, $exchangeName) {
                        $prices = $container->getAllPairsPricesHandle($response);
                        foreach ($pairs as $k => $pair) {
                            // if coins must be replaced
                            if ($pair[2]) {
                                $exchangePair = $container->getPair($pair[1], $pair[0]);
                            } else {
                                $exchangePair = $container->getPair($pair[0], $pair[1]);
                            }

                            $price = $prices[$exchangePair] ?? null;

                            if (!$price) {
                                continue;
                            }

                            if ($pair[3] === 'crypto') {
                                if (is_callable($convertCryptoCallback)) {
                                    $price = $convertCryptoCallback($pair[1], $price, $pair[4]);
                                }
                            } elseif ($pair[3] === 'fiat' || $container->isFiat()) {
                                $fiatCurrency = $container->isFiat() ?? $pair[1];
                                if (is_callable($convertFiatCallback)) {
                                    $price = $convertFiatCallback($fiatCurrency, $price);
                                }
                            }
                            self::$prices[$exchangeName][$k] = round($price, 8);
                        }
                    },
                    null,
                    $options,
                    null
                );
                continue;
            } else {
                foreach ($pairs as $k => $pair) {
                    try {

                        // if coins must be replaced
                        if ($pair[2]) {
                            $requestUrl = $container->getPairPriceUrl($pair[1], $pair[0]);
                        } else {
                            $requestUrl = $container->getPairPriceUrl($pair[0], $pair[1]);
                        }

                        if (is_array($requestUrl)) {
                            $url = $container->uri_construct($requestUrl['uri'], $requestUrl['params']);
                        } else {
                            $url = $container->uri_construct($requestUrl);
                        }

                        $rcx->addRequest(
                            $url,
                            null,
                            function($response, $url, $request_info, $user_data, $time) use ($container, $pair, $convertFiatCallback, $convertCryptoCallback, $exchangeName, $k) {
                                // if coins must be replaced
                                if ($pair[2]) {
                                    $price = $container->getPairPriceHandle($response, $pair[1], $pair[0]);
                                } else {
                                    $price = $container->getPairPriceHandle($response, $pair[0], $pair[1]);
                                }

                                if (!$price) {
                                    return;
                                }

                                if ($pair[3] === 'crypto') {
                                    if (is_callable($convertCryptoCallback)) {
                                        $price = $convertCryptoCallback($pair[1], $price, $pair[4]);
                                    }
                                } elseif ($pair[3] === 'fiat' || $container->isFiat()) {
                                    $fiatCurrency = $container->isFiat() ?? $pair[1];
                                    if (is_callable($convertFiatCallback)) {
                                        $price = $convertFiatCallback($fiatCurrency, $price);
                                    }
                                }
                                self::$prices[$exchangeName][$k] = round($price, 8);
                            },
                            null,
                            $options,
                            null
                        );
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        try {
            $rcx->execute();
        } catch(\Exception $e) {
            //
        }

        $prices = self::$prices;
        self::$prices = [];

        return $prices;
    }

    /**
     * @param $data - see format in config
     * @param null $convertFiatCallback
     * @param null $convertCryptoCallback
     * @param int $timeout
     * @param int $concurrent_requests
     * @return mixed
     */
    public function getCoinData($data, $convertFiatCallback = null, $convertCryptoCallback = null, $timeout = 3000, $concurrent_requests = 10)
    {
        $containers = self::getExchangeContainers(array_keys($data));
        $rcx = new RollingCurlX($concurrent_requests);
        $rcx->setTimeout($timeout);
        $options = [CURLOPT_USERAGENT => $this->userAgent];
        foreach ($data as $exchangeName => $pairs) {
            $container = $containers[$exchangeName];
            foreach ($pairs as $k => $pair) {
                // if coins must be replaced
                if ($pair[2]) {
                    $lastTradeDataUrl = $container->getLastTradeDataUrl($pair[1], $pair[0]);
                } else {
                    $lastTradeDataUrl = $container->getLastTradeDataUrl($pair[0], $pair[1]);
                }

                if (is_array($lastTradeDataUrl)) {
                    $url = $container->uri_construct($lastTradeDataUrl['uri'], $lastTradeDataUrl['params']);
                } else {
                    $url = $container->uri_construct($lastTradeDataUrl);
                }

                $rcx->addRequest(
                    $url,
                    null,
                    function($response, $url, $request_info, $user_data, $time) use ($container, $pair, $convertFiatCallback, $convertCryptoCallback, $exchangeName) {
                        try {
                            // if coins must be replaced
                            if ($pair[2]) {
                                $data = $container->getLastTradeDataHandle($response, $pair[1], $pair[0]);
                            } else {
                                $data = $container->getLastTradeDataHandle($response, $pair[0], $pair[1]);
                            }
                        } catch (\Exception $e) {
                            return;
                        }

                        if (!$data) {
                            return;
                        }

                        $price = $data['price'];

                        if ($pair[3] === 'crypto') {
                            if (is_callable($convertCryptoCallback)) {
                                $price = $convertCryptoCallback($pair[1], $price, $pair[4]);
                            }
                        } elseif ($pair[3] === 'fiat' || $container->isFiat()) {
                            $fiatCurrency = $container->isFiat() ?? $pair[1];
                            if (is_callable($convertFiatCallback)) {
                                $price = $convertFiatCallback($fiatCurrency, $price);
                            }
                        }

                        if ($price) {
                            $data['price'] = $price;
                            $data['sum'] = $price * $data['volume'];
                        }

                        if ($data['price']) {
                            self::$buffer[$exchangeName]['lastTradeData'] = $data;
                        }
                    },
                    null,
                    $options,
                    null
                );

                // if coins must be replaced
                if ($pair[2]) {
                    $totalVolumeUrl = $container->getTotalVolumeUrl($pair[1], $pair[0]);
                } else {
                    $totalVolumeUrl = $container->getTotalVolumeUrl($pair[0], $pair[1]);
                }

                if (is_array($totalVolumeUrl)) {
                    $url = $container->uri_construct($totalVolumeUrl['uri'], $totalVolumeUrl['params']);
                } else {
                    $url = $container->uri_construct($totalVolumeUrl);
                }

                $rcx->addRequest(
                    $url,
                    null,
                    function($response, $url, $request_info, $user_data, $time) use ($container, $pair, $convertFiatCallback, $convertCryptoCallback, $exchangeName) {
                        try {
                            // if coins must be replaced
                            if ($pair[2]) {
                                $volume = $container->getTotalVolumeHandle($response, $pair[1], $pair[0]);
                            } else {
                                $volume = $container->getTotalVolumeHandle($response, $pair[0], $pair[1]);
                            }
                        } catch (\Exception $e) {
                            return;
                        }

                        if ($volume) {
                            self::$buffer[$exchangeName]['totalVolume'] = $volume;
                        }
                    },
                    null,
                    $options,
                    null
                );

                // if coins must be replaced
                if ($pair[2]) {
                    $totalDemandAndOfferUrl = $container->getTotalDemandAndOfferUrl($pair[1], $pair[0]);
                } else {
                    $totalDemandAndOfferUrl = $container->getTotalDemandAndOfferUrl($pair[0], $pair[1]);
                }

                if (is_array($totalDemandAndOfferUrl)) {
                    $url = $container->uri_construct($totalDemandAndOfferUrl['uri'], $totalDemandAndOfferUrl['params']);
                } else {
                    $url = $container->uri_construct($totalDemandAndOfferUrl);
                }

                $rcx->addRequest(
                    $url,
                    null,
                    function($response, $url, $request_info, $user_data, $time) use ($container, $pair, $convertFiatCallback, $convertCryptoCallback, $exchangeName) {
                        try {
                            // if coins must be replaced
                            if ($pair[2]) {
                                $data = $container->getTotalDemandAndOfferHandle($response, $pair[1], $pair[0]);
                            } else {
                                $data = $container->getTotalDemandAndOfferHandle($response, $pair[0], $pair[1]);
                            }
                        } catch (\Exception $e) {
                            return;
                        }

                        if (!$data) {
                            return;
                        }

                        $demand = $data['totalDemand'];

                        if ($pair[3] === 'crypto') {
                            if ($exchangeName === 'huobi' && $pair[0] === 'BTC') {
                                $pair[4] = false;
                            }
                            if (is_callable($convertCryptoCallback)) {
                                $demand = $convertCryptoCallback($pair[1], $demand, $pair[4]);
                            }
                        } elseif ($pair[3] === 'fiat' || $container->isFiat()) {
                            $fiatCurrency = $container->isFiat() ?? $pair[1];
                            if (is_callable($convertFiatCallback)) {
                                $demand = $convertFiatCallback($fiatCurrency, $demand);
                            }
                        }

                        if ($demand) {
                            $data['totalDemand'] = $demand;
                        }

                        if ($data['totalDemand']) {
                            self::$buffer[$exchangeName]['totalDemandUsd'] = round($data['totalDemand'], 8);
                            self::$buffer[$exchangeName]['totalOffer'] = $data['totalOffer'];
                        }
                    },
                    null,
                    $options,
                    null
                );
            }
        }

        $rcx->execute();

        $buffer = self::$buffer;
        self::$buffer = [];

        return $buffer;
    }

    /**
     * Return avg of pair from all stock exchanges
     *
     * @param $data - see data format in config
     * @param null $convertFiatCallback
     * @param null $convertCryptoCallback
     * @param array $exchangesAllPrices
     * @return float|null
     */
    public static function getTickerAverage($data, $convertFiatCallback = null, $convertCryptoCallback = null, $exchangesAllPrices = [])
    {
        $allPrices = self::getAllPrices($data, $convertFiatCallback, $convertCryptoCallback, $exchangesAllPrices);

        if (!count($allPrices)) {
            return null;
        }

        $prices_list = [];

        foreach ($allPrices as $prices) {
            foreach ($prices as $price) {
                $prices_list[] = $price;
            }
        }

        return round(array_sum($prices_list) / count($prices_list), 8);
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
     * @param null|array $only
     * @return array
     */
    protected static function getExchangeContainers($only = null)
    {
        if (!($availableStocks = config('exchange.available'))) {
            $config = require_once('../Config/exchange.php');
            $availableStocks = $config['available'];
        }
        $availableStocks = array_keys($availableStocks);

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
    public function getPairPrice($first_currency = 'BTC', $second_currency = 'USD')
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
     * Get last trade data
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getLastTradeData($first_currency = 'BTC', $second_currency = 'USD')
    {
        $url = $this->getLastTradeDataUrl($first_currency, $second_currency);

        if (is_array($url)) {
            $response = $this->api_request($url['uri'], $url['params']);
        } else {
            $response = $this->api_request($url);
        }

        return $this->getLastTradeDataHandle($response, $first_currency, $second_currency);
    }

    /**
     * Get total volume
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return null|float
     */
    public function getTotalVolume($first_currency = 'BTC', $second_currency = 'USD')
    {
        $url = $this->getTotalVolumeUrl($first_currency, $second_currency);

        if (is_array($url)) {
            $response = $this->api_request($url['uri'], $url['params']);
        } else {
            $response = $this->api_request($url);
        }

        return $this->getTotalVolumeHandle($response, $first_currency, $second_currency);
    }

    /**
     * Get total demand and offer
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getTotalDemandAndOffer($first_currency = 'BTC', $second_currency = 'USD')
    {
        $url = $this->getTotalDemandAndOfferUrl($first_currency, $second_currency);

        if (is_array($url)) {
            $response = $this->api_request($url['uri'], $url['params']);
        } else {
            $response = $this->api_request($url);
        }

        return $this->getTotalDemandAndOfferHandle($response);
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

    /**
     * @return bool
     */
    public function needV2()
    {
        return $this->needV2 ?? false;
    }
}
