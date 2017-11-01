<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Btc38
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Btc38 extends StockExchange
{
    public $api_uri = 'http://api.btc38.com/v1';
    protected $fiat = 'CNY';
    protected $onlyFiat = true;

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
     * @return null|array
     */
    public function getAvailableCoins()
    {
        $responseJSON = $this->api_request("ticker.php", ['c' => 'all']);
        $response = json_decode($responseJSON, true);

        if (!$response || !is_array($response)) {
            return null;
        }

        return array_keys($response);
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $c = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "ticker.php",
            'params' => compact('c'),
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

        if (!$response || !is_array($response) || !isset($response['ticker']) || !isset($response['ticker']['last'])) {
            return null;
        }

        return (float) $response['ticker']['last'];
    }

    public function getChartData($first_currency = 'BTC', $second_currency = 'CNY')
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
    private function getPair($first_currency = 'BTC', $second_currency = 'CNY')
    {
        return strtolower($first_currency);
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
        $c = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "trades.php",
            'params' => compact('c'),
        ];
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

        if (!$response || !is_array($response)) {
            return null;
        }

        $lastTrade = $response[count($response) - 1];

        $sum = round($lastTrade['price'] * $lastTrade['amount'], 8);
        $volume = $lastTrade['amount'];

        return compact('sum', 'volume');
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
        $c = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "ticker.php",
            'params' => compact('c'),
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
        $response = json_decode($response, true);

        if (!$response || !is_array($response)) {
            return null;
        }

        return $response['ticker']['vol'];
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
        $c = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "depth.php",
            'params' => compact('c'),
        ];
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

        if (!$response || !is_array($response)) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['asks'] as $ask) {
            $totalDemand += $ask[0] * $ask[1];
        }

        $offersAmounts = array_column($response['bids'], 1);

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }
}