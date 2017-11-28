<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Novaexchange
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Novaexchange extends StockExchange
{
    public $api_uri = 'https://novaexchange.com/remote/v2';

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
        $currencyPair = $this->getPair($first_currency, $second_currency);

        return "market/info/{$currencyPair}/";
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

        if (!$response || $response['status'] !== 'success') {
            return null;
        }

        return (float) $response['markets'][0]['last_price'];
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
        $currencyPair = $this->getPair($first_currency, $second_currency);

        return "market/orderhistory/{$currencyPair}/";
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

        if (!$response || $response['status'] !== 'success') {
            return null;
        }

        $lastTrade = $response['items'][0];

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
     * @return string
     */
    public function getTotalVolumeUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $currencyPair = $this->getPair($first_currency, $second_currency);

        return "market/info/{$currencyPair}/";
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

        if (!$response || $response['status'] !== 'success') {
            return null;
        }

        return (float) $response['markets'][0]['volume24h'];
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
        $currencyPair = $this->getPair($first_currency, $second_currency);

        return "market/openorders/{$currencyPair}/BOTH/";
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

        if (!$response || $response['status'] !== 'success') {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['buyorders'] as $ask) {
            $totalDemand += $ask['price'] * $ask['amount'];
        }

        $offersAmounts = array_column($response['sellorders'], 'amount');

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
        return $first_currency . '_' . $second_currency;
    }
}