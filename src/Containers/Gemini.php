<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Gemini
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Gemini extends StockExchange
{
    public $api_uri = 'https://api.gemini.com/v1';

    public function getAvailableQuotation()
    {
        return null;
    }

    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        return null;
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @param null $timestamp
     * @param null $limit_trades
     * @param null $include_breaks
     * @return mixed|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD', $timestamp = null, $limit_trades = null, $include_breaks = null)
    {
        $data = [];
        if ($timestamp) {
            $data['timestamp'] = $timestamp;
        }
        if ($limit_trades) {
            $data['limit_trades'] = $limit_trades;
        }
        if ($include_breaks) {
            $data['include_breaks'] = $include_breaks;
        }

        $pair = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request("trades/{$pair}", $data);
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['result'])) {
            return null;
        }

        return $response;
    }

    /**
     * Return available coins
     *
     * @return array
     */
    public function getAvailableCoins()
    {
        return ['BTC', 'ETH'];
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "pubticker/{$pair}";
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

        if (!$response || isset($response['result'])) {
            return null;
        }

        return (float) $response['last'];
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
        return strtolower($first_currency . $second_currency);
    }
}