<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Hitbtc
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Hitbtc extends StockExchange
{
    public $api_uri = 'http://api.hitbtc.com/api/1/public';

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
     * @param int $from
     * @param string $by
     * @param int $start_index
     * @return array|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD', $from = 0, $by = 'timestamp', $start_index = 0)
    {
        $data = [];
        $data['from'] = $from;
        $data['by'] = $by;
        $data['start_index'] = $start_index;

        $pair = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request("{$pair}/trades", $data);
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['error'])) {
            return null;
        }

        return $response;
    }

    /**
     * Return available coins
     *
     * @return null|array
     */
    public function getAvailableCoins()
    {
        $responseJSON = $this->api_request("symbols");
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['error'])) {
            return null;
        }

        $coins = [];

        foreach ($response['symbols'] as $symbol) {
            if (in_array($symbol['currency'], $coins)) {
                continue;
            }
            $coins[] = $symbol['currency'];
            if (in_array($symbol['commodity'], $coins)) {
                continue;
            }
            $coins[] = $symbol['commodity'];
        }

        return $coins;
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
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

        if (!$response || isset($response['error'])) {
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
        if ($first_currency === 'BCH') {
            $first_currency = 'BCC';
        }
        if ($second_currency === 'BCH') {
            $second_currency = 'BCC';
        }

        return $first_currency . $second_currency;
    }
}