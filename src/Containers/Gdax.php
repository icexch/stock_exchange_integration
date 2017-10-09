<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

use Carbon\Carbon;

/**
 * Class Gdax
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Gdax extends StockExchange
{
    public $api_uri = 'https://api.gdax.com';

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
     * @param null $before
     * @param null $after
     * @param null $limit
     * @return array|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD', $before = null, $after = null, $limit = null)
    {
        $data = [];

        if ($before) {
            $data['before'] = $before;
        }
        if ($after) {
            $data['after'] = $after;
        }
        if ($limit) {
            $data['limit'] = $limit;
        }

        $pair = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request("products/{$pair}/trades", $data);
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['message'])) {
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
        $responseJSON = $this->api_request("currencies");
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['message'])) {
            return null;
        }

        return array_column($response, 'id');
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "products/{$pair}/ticker";
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

        if (!$response || isset($response['message'])) {
            return null;
        }

        return (float) $response['price'];
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @param null $start
     * @param null $end
     * @param null $granularity
     * @return array|null
     */
    public function getChartData($first_currency = 'BTC', $second_currency = 'USD', $start = null, $end = null, $granularity = null)
    {
        $data = [];
        if ($start) {
            $data['start'] = Carbon::createFromTimestamp($start)->toIso8601String();
            $data['end'] = Carbon::createFromTimestamp($end)->toIso8601String();
        }
        if ($granularity) {
            $data['granularity'] = $granularity;
        }

        $pair = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request("products/{$pair}/candles", $data);
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['message'])) {
            return null;
        }

        $returnData = [];

        foreach ($response as $item) {
            $currentData = [];
            $currentData['timestamp'] = $item[0];
            $currentData['low'] = $item[1];
            $currentData['high'] = $item[2];
            $currentData['open'] = $item[3];
            $currentData['close'] = $item[4];
            $currentData['advancedData'] = [
                'volume' => $item[5],
            ];
            $returnData[] = $currentData;
        }

        return $returnData;
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
        return $first_currency . '-' . $second_currency;
    }
}