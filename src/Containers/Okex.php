<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Okex
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Okex extends StockExchange
{
    public $api_uri = 'https://www.okex.com/api/v1';

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
            'BCC',
        ];
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'ticker.do',
            'params' => compact('symbol'),
        ];
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

        if (!$response || isset($response['error_code'])) {
            return null;
        }

        return (float) $response['ticker']['last'];
    }

    public function getChartData($first_currency = 'BTC', $second_currency = 'USD')
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
    public function getLastTradeDataUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'trades.do',
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
    public function getLastTradeDataHandle($response, $first_currency = 'BTC', $second_currency = 'USD')
    {
        $response = json_decode($response, true);

        if (!$response || isset($response['error_code'])) {
            return null;
        }

        $lastTrade = $response[count($response) - 1];

        $sum = round($lastTrade['price'] * $lastTrade['amount'], 8);
        $volume = (float) $lastTrade['amount'];
        $price = (float) $lastTrade['price'];

        return compact('sum', 'volume', 'price');
    }

    /**
     * Get total volume url
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getTotalVolumeUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'ticker.do',
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
    public function getTotalVolumeHandle($response, $first_currency = 'BTC', $second_currency = 'USD')
    {
        $response = json_decode($response, true);

        if (!$response || isset($response['error_code'])) {
            return null;
        }

        return (float) $response['ticker']['vol'];
    }

    /**
     * get total demand and offer
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getTotalDemandAndOfferUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'depth.do',
            'params' => compact('symbol'),
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

        if (!$response || isset($response['error_code'])) {
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
    private function getPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        if ($first_currency === 'IOT') {
            $first_currency = 'iota';
        }

        if ($second_currency === 'USD') {
            $second_currency = 'USDT';
        }

        return strtolower($first_currency . '_' . $second_currency);
    }
}