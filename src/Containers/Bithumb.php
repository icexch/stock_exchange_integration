<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Bithumb
 *
 * https://api.bithumb.com/public/
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Bithumb extends StockExchange
{
    public $api_uri = 'https://api.bithumb.com/public';

    /*Currency return instead USD*/
    protected $fiat = 'KRW';
    protected $onlyFiat = true;

    public function getAvailableQuotation()
    {
        return null;
    }

    /**
     * Return exchange last transaction information
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return null|array
     */
    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        $responseJSON = $this->api_request('ticker/' . $first_currency);
        $response = json_decode($responseJSON, true);

        if ($response['status'] !== "0000") {
            return null;
        }

        return $response['data'];
    }

    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'NXT', $start = null, $end = null)
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
        $responseJSON = $this->api_request('ticker/all');
        $response = json_decode($responseJSON, true)['data'];

        $response = array_filter($response, function ($item) {
            return is_array($item);
        });

        return array_keys($response);
    }

    /**
     * Get pair price
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return float|null
     */
    public function getPairPrice($first_currency = 'BTC', $second_currency = 'USD')
    {
        $responseJSON = $this->api_request('ticker/' . $first_currency);
        $response = json_decode($responseJSON, true);

        if ($response['status'] !== "0000") {
            return null;
        }

        return (float) $response['data']['sell_price'];
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        return 'ticker/' . $first_currency;
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

        if ($response['status'] !== "0000") {
            return null;
        }

        return (float) $response['data']['sell_price'];
    }

    public function getChartData($first_currency = 'BTC', $second_currency = 'USD')
    {
        return null;
    }
}