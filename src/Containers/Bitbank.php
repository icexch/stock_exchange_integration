<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Bitbank
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Bitbank extends StockExchange
{
    public $api_uri = 'https://public.bitbank.cc';
    protected $fiat = 'JPY';

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
     * @return string
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "{$pair}/ticker";
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


        if (!$response || ! (bool) $response['success']) {
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
     * @return string
     */
    public function getLastTradeDataUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "{$pair}/transactions";
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

        if (!$response || ! (bool) $response['success']) {
            return null;
        }

        $price = (float) $response['data']['transactions'][0]['price'];
        $volume = (float) $response['data']['transactions'][0]['amount'];
        $sum = round($price * $volume, 8);

        return compact('sum', 'volume', 'price');
    }

    /**
     * Get total volume url
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    public function getTotalVolumeUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "{$pair}/ticker";
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

        if (!$response || ! (bool) $response['success']) {
            return null;
        }

        return (float) $response['data']['vol'];
    }

    /**
     * get total demand and offer
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    public function getTotalDemandAndOfferUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "{$pair}/depth";
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

        if (!$response || ! (bool) $response['success']) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['data']['bids'] as $ask) {
            $totalDemand += $ask[0] * $ask[1];
        }

        $offersAmounts = array_column($response['data']['asks'], 1);

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
    private function getPair($first_currency = 'BTC', $second_currency = 'JPY')
    {
        return strtolower($first_currency . '_' . $second_currency);
    }
}