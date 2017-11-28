<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Huobi
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Huobi extends StockExchange
{
    public $api_uri = 'https://be.huobi.com';

    private $api_uri1 = 'https://be.huobi.com'; // ETH/CNY, ETC/CNY, BCC/CNY
    private $api_uri2 = 'https://api.huobi.pro'; // ETH/BTC, LTC/BTC, ETC/BTC, BCC/BTC

    protected $fiat = 'CNY';

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
        $responseJSON = $this->api_request("/v1/common/currencys");
        $response = json_decode($responseJSON, true);

        if (!$response || $response['status'] !== 'ok') {
            return null;
        }

        return $response['data'];
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "market/detail",
            'params' => compact('symbol'),
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

        if (!$response || $response['status'] !== 'ok') {
            return null;
        }

        return (float) $response['tick']['close'];
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
    private function getPair($first_currency = 'ETH', $second_currency = 'CNY')
    {
        if ($second_currency === 'USD') {
            $second_currency = 'CNY';
        }
        if ($first_currency === 'BCH') {
            $first_currency = 'BCC';
        }
        if ($second_currency === 'BCH') {
            $second_currency = 'BCC';
        }
        $this->api_uri = $second_currency !== 'CNY' ? $this->api_uri2 : $this->api_uri1;

        return strtolower($first_currency . $second_currency);
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
            'uri' => "market/history/trade",
            'params' => compact('symbol'),
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

        if (!$response || $response['status'] !== 'ok') {
            return null;
        }

        $sum = round($response['data'][0]['data'][0]['price'] * $response['data'][0]['data'][0]['amount'], 8);
        $volume = (float) $response['data'][0]['data'][0]['amount'];
        $price = (float) $response['data'][0]['data'][0]['price'];

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
        $symbol = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "market/detail",
            'params' => compact('symbol'),
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

        if (!$response || $response['status'] !== 'ok') {
            return null;
        }

        return (float) $response['tick']['vol'];
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
        $type = 'step0';

        return [
            'uri' => "/market/depth",
            'params' => compact('symbol', 'type'),
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

        if (!$response || $response['status'] !== 'ok') {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['tick']['asks'] as $ask) {
            $totalDemand += $ask[0] * $ask[1];
        }

        $offersAmounts = array_column($response['tick']['bids'], 1);

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }
}