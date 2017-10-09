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

    public function getChartData($first_currency = 'BTC', $second_currency = 'USDT')
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
    private function getPair($first_currency = 'BTC', $second_currency = 'USDT')
    {
        if ($second_currency === 'USD') {
            $second_currency .= 'T';
        }

        return $first_currency . $second_currency;
    }
}