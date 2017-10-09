<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Zaif
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Zaif extends StockExchange
{
    public $api_uri = 'https://api.zaif.jp/api/1';
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
     * @return null
     */
    public function getAvailableCoins()
    {
        return null;
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "last_price/{$pair}";
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

        if (!$response || isset($response['error'])) {
            return null;
        }

        return (float) $response['last_price'];
    }

    public function getChartData($first_currency = 'BTC', $second_currency = 'JPY')
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
    private function getPair($first_currency = 'BTC', $second_currency = 'JPY')
    {
        if ($second_currency === 'USD') {
            $second_currency = 'JPY';
        }

        return strtolower($first_currency . '_' . $second_currency);
    }
}