<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Binance
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Binance extends StockExchange
{
    public $api_uri = 'https://www.binance.com/api/v1';

    public function getAvailableQuotation()
    {
        return null;
    }

    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        return null;
    }

    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD')
    {
        return null;
    }

    /**
     * Return available coins
     *
     * @return array
     */
    public function getAvailableCoins()
    {
        return [
            'BTC',
            'WTC',
            'NEO',
            'LINK',
            'BNB',
            'BNB',
            'QTUM',
            'SALT',
        ];
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        return "ticker/allPrices";
    }

    /**
     * Get price from response
     *
     * @param $response
     * @param $first_currency
     * @param $second_currency
     * @return float|null
     */
    public function getPairPriceHandle($response, $first_currency, $second_currency)
    {
        $response = json_decode($response, true);

        if (!$response) {
            return null;
        }

        $pair = $this->getPair($first_currency, $second_currency);

        $prices = array_column($response, 'price', 'symbol');

        if (!isset($prices[$pair])) {
            return null;
        }

        return (float) $prices[$pair];
    }

    /**
     * @return string|array
     */
    public function getAllPairsPricesUrl()
    {
        return [
            'uri' => "ticker/allPrices",
            'params' => [],
        ];
    }

    /**
     * @param $response
     * @return array|string
     */
    public function getAllPairsPricesHandle($response)
    {
        $response = json_decode($response, true);

        if (!$response) {
            return null;
        }

        return array_column($response, 'price', 'symbol');
    }

    public function getChartData($first_currency = 'BTC', $second_currency = 'USDT')
    {
        return null;
    }

    /**
     * Get last trade data url
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getLastTradeDataUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'aggTrades',
            'params' => compact('symbol'),
        ];
    }

    /**
     * Get last trade data handle
     *
     * @param string $response
     * @param string $first_currency
     * @param string $second_currency
     * @return array|null
     */
    public function getLastTradeDataHandle($response, $first_currency = 'BTC', $second_currency = 'USDT')
    {
        $response = json_decode($response, true);

        if (!$response) {
            return null;
        }

        $lastTrade = $response[count($response) - 1];

        $price = (float) $lastTrade['p'];
        $sum =  round($lastTrade['p'] * $lastTrade['q'], 8);
        $volume = (float) $lastTrade['q'];

        return compact('sum', 'volume', 'price');
    }

    /**
     * Get total volume url
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getTotalVolumeUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'ticker/24hr',
            'params' => compact('symbol'),
        ];
    }

    /**
     * Get total volume handle
     *
     * @param string $response
     * @param string $first_currency
     * @param string $second_currency
     * @return null|float
     */
    public function getTotalVolumeHandle($response, $first_currency = 'BTC', $second_currency = 'USDT')
    {
        $response = json_decode($response, true);

        if (!$response) {
            return null;
        }

        return (float) $response['volume'];
    }

    /**
     * get total demand and offer
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getTotalDemandAndOfferUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'depth',
            'params' => compact('symbol')
        ];
    }

    /**
     * get total demand
     *
     * @param string $response
     * @return float|null
     */
    public function getTotalDemandAndOfferHandle($response)
    {
        $response = json_decode($response, true);

        if (!$response) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['bids'] as $ask) {
            $totalDemand += $ask[0] * $ask[1];
        }

        $offersAmounts = array_column($response['asks'], 1);

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }

    /**
     * get pair
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    public function getPair($first_currency = 'BTC', $second_currency = 'USDT')
    {
        if ($second_currency === 'USD') {
            $second_currency .= 'T';
        }

        if ($second_currency === 'IOT') {
            $second_currency .= 'A';
        }
        if ($first_currency === 'IOT') {
            $first_currency .= 'A';
        }

        return $first_currency . $second_currency;
    }
}