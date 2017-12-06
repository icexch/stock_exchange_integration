<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Bitz
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Bitz extends StockExchange
{
    public $api_uri = 'https://www.bit-z.com/api_v1';

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
        $coin = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'ticker',
            'params' => compact('coin')
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

        if (!$response || !$response['data']) {
            return null;
        }

        return (float) $response['data']['last'];
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
        $coin = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'orders',
            'params' => compact('coin')
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

        if (!$response || !$response['data']) {
            return null;
        }

        $lastTrade = $response['data']['d'][0];

        $sum = round($lastTrade['p'] * $lastTrade['n'], 8);
        $volume = (float) $lastTrade['n'];
        $price = (float) $lastTrade['p'];

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
        $coin = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'ticker',
            'params' => compact('coin')
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

        if (!$response || !$response['data']) {
            return null;
        }

        return (float) $response['data']['vol'];
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
        $coin = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'depth',
            'params' => compact('coin')
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

        if (!$response || !$response['data']) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['data']['asks'] as $ask) {
            $totalDemand += $ask[0] * $ask[1];
        }

        $offersAmounts = array_column($response['data']['bids'], 1);

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
        return strtolower($first_currency . '_' . $second_currency);
    }
}