<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Livecoin
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Livecoin extends StockExchange
{
    public $api_uri = 'https://api.livecoin.net/';

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
        $currencyPair = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'exchange/ticker',
            'params' => compact('currencyPair'),
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

        if (!$response || isset($response['success'])) {
            return null;
        }

        return (float) $response['last'];
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
        return null;
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
        return null;
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
        return null;
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
        return null;
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
        return null;
    }

    /**
     * get total demand
     *
     * @param string $response
     * @return float|null
     */
    public function getTotalDemandAndOfferHandle($response)
    {
        return null;
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
        return $first_currency . '/' . $second_currency;
    }
}