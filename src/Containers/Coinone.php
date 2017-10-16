<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Coinone
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Coinone extends StockExchange
{
    public $api_uri = 'https://api.coinone.co.kr';
    protected $fiat = 'KRW';
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
     * @return array
     */
    public function getAvailableCoins()
    {
        return [
            'btc',
            'bch',
            'eth',
            'etc',
            'xrp',
            'qtum',
        ];
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $currency = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "ticker",
            'params' => compact('currency'),
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

        if (!$response || $response['result'] !== 'success' || !isset($response['last'])) {
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
        $currency = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "trades",
            'params' => compact('currency'),
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

        if (!$response || $response['result'] !== 'success') {
            return null;
        }

        $lastTrade = $response['completeOrders'][count($response['completeOrders']) - 1];

        $sum = round($lastTrade['price'] * $lastTrade['qty'], 8);
        $volume = (float) $lastTrade['qty'];

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
        $currency = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "ticker",
            'params' => compact('currency'),
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

        if (!$response || $response['result'] !== 'success') {
            return null;
        }

        return (float) $response['volume'];
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
        $currency = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "orderbook",
            'params' => compact('currency'),
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

        if (!$response || $response['result'] !== 'success') {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['ask'] as $ask) {
            $totalDemand += $ask['price'] * $ask['qty'];
        }

        $offersAmounts = array_column($response['bid'], 'qty');

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }
}