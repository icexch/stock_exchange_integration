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
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
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
}