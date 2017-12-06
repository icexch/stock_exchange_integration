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

    /**
     * Get last trade data url
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    public function getLastTradeDataUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        return 'recent_transactions/' . $first_currency;
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

        if ($response['status'] !== "0000") {
            return null;
        }

        $sum = (float) $response['data'][0]['total'];
        $volume = (float) $response['data'][0]['units_traded'];
        $price = (float) $response['data'][0]['price'];

        return compact('sum', 'volume', 'price');
    }

    /**
     * Get total volume url
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    public function getTotalVolumeUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        return 'ticker/' . $first_currency;
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

        if ($response['status'] !== "0000") {
            return null;
        }

        return (float) $response['data']['volume_1day'];
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
        return 'orderbook/' . $first_currency;
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

        if ($response['status'] !== "0000") {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['data']['asks'] as $ask) {
            $totalDemand += $ask['price'] * $ask['quantity'];
        }

        $offersAmounts = array_column($response['data']['bids'], 'quantity');

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }
}