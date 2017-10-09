<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Bittrex
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Bittrex extends StockExchange
{
    public $api_uri = 'https://bittrex.com/api/v1.1/public';

    public function getAvailableQuotation()
    {
        return null;
    }

    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        return null;
    }

    /**
     * Used to retrieve the latest trades that have occured for a specific market.
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD')
    {
        $market = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request('getmarkethistory', compact('market'));
        $response = json_decode($responseJSON, true);

        if (!$response || $response['success'] !== true) {
            return null;
        }

        return $response['result'];
    }

    /**
     * Return available coins
     *
     * @return null|array
     */
    public function getAvailableCoins()
    {
        $responseJSON = $this->api_request('getcurrencies');
        $response = json_decode($responseJSON, true);

        if (!$response || $response['success'] !== true) {
            return null;
        }

        $coins = $response['result'];
        $coins = array_filter($coins, function ($item) {
            return $item['IsActive'] === true;
        });

        $coins = array_column($coins, 'Currency');

        return $coins;
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $market = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "getticker",
            'params' => compact('market'),
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

        if (!$response || $response['success'] !== true) {
            return null;
        }

        return (float) $response['result']['Last'];
    }

    public function getChartData($first_currency = 'BTC', $second_currency = 'USD')
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
        if ($second_currency === 'USD') {
            $second_currency .= 'T';
        }

        if ($first_currency === 'BCH') {
            $first_currency = 'BCC';
        }
        if ($second_currency === 'BCH') {
            $second_currency = 'BCC';
        }

        return $second_currency . '-' . $first_currency;
    }
}