<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Coinfalcon
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Coinfalcon extends StockExchange
{
    public $api_uri = 'https://coinfalcon.com/api/v1';

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
        return [
            'uri' => 'markets',
            'params' => [],
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
        $symbol = $this->getPair($first_currency, $second_currency);

        $response = json_decode($response, true);

        if (!$response || !isset($response['data'])) {
            return null;
        }

        $prices = array_column($response['data'], 'last_price', 'name');

        if (!isset($prices[$symbol])) {
            return null;
        }

        return (float) $prices[$symbol];
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
            'uri' => "markets/{$symbol}/trades",
            'params' => [],
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

        if (!$response || !isset($response['data'])) {
            return null;
        }

        $lastTrade = $response['data'][0];

        $sum = round($lastTrade['price'] * $lastTrade['size'], 8);
        $volume = (float) $lastTrade['size'];
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
        return [
            'uri' => 'markets',
            'params' => [],
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
        $symbol = $this->getPair($first_currency, $second_currency);

        $response = json_decode($response, true);

        if (!$response || !isset($response['data'])) {
            return null;
        }

        $prices = array_column($response['data'], 'volume', 'name');

        if (!isset($prices[$symbol])) {
            return null;
        }

        return (float) $prices[$symbol];
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
            'uri' => "markets/{$symbol}/orders",
            'params' => [
                'level' => 3,
            ],
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

        if (!$response || !isset($response['data'])) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['data']['asks'] as $ask) {
            $totalDemand += $ask['price'] * $ask['size'];
        }

        $offersAmounts = array_column($response['data']['bids'], 'size');

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
        return strtoupper($first_currency . '-' . $second_currency);
    }
}