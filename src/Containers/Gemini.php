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

        return "trades/{$pair}";
    }

    /**
     * Get last trade data handle
     *
     * @param string $response
     * @param string $first_currency
     * @param string $second_currency
     * @return float|null
     */
    public function getLastTradeDataHandle($response, $first_currency = 'BTC', $second_currency = 'USD')
    {
        $response = json_decode($response, true);

        if (!$response || isset($response['result'])) {
            return null;
        }

        $sum = round($response[0]['price'] * $response[0]['amount'], 8);
        $volume = (float) $response[0]['amount'];
        $price = (float) $response[0]['price'];

        return compact('sum', 'volume' ,'price');
    }

    /**
     * Get total volume url
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return null|float
     */
    public function getTotalVolumeUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "pubticker/{$pair}";
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

        if (!$response || isset($response['result'])) {
            return null;
        }

        return (float) $response['volume'][$first_currency];
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

        return "book/{$pair}";
    }

    /**
     * get total demand and offer
     *
     * @param string $response
     * @return float|null
     */
    public function getTotalDemandAndOfferHandle($response)
    {
        $response = json_decode($response, true);

        if (!$response || isset($response['result'])) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['asks'] as $ask) {
            $totalDemand += $ask['price'] * $ask['amount'];
        }

        $offersAmounts = array_column($response['bids'], 'amount');

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }
}