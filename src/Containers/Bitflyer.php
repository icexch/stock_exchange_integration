<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Bitflyer
 *
 * https://api.bithumb.com/public/
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Bitflyer extends StockExchange
{

	public $api_uri = 'https://api.bitflyer.jp/v1';

	public function getAvailableQuotation()
	{
        return null;
	}

    /**
     * Last ticker info
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array|null
     */
    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
	{
        $product_code = $first_currency . '_' . $second_currency;

        $responseJSON = $this->api_request('ticker/', compact('product_code'));
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['error_message'])) {
            return null;
        }

        return $response;
    }

    /**
     * Order Book
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD')
	{
        $product_code = $first_currency . '_' . $second_currency;

        $responseJSON = $this->api_request('board/', compact('product_code'));
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['error_message'])) {
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
        $pricesJSON = $this->api_request('getprices');
        $prices = json_decode($pricesJSON, true);

        if (!$prices || isset($prices['error_message'])) {
            return null;
        }

        $coins = [];

        foreach ($prices as $pair) {
            if (!in_array($pair['main_currency'], $coins)) {
                $coins[] = $pair['main_currency'];
            }
            if (!in_array($pair['sub_currency'], $coins)) {
                $coins[] = $pair['sub_currency'];
            }
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
        $product_code = $first_currency . '_' . $second_currency;

        return [
            'uri' => "getticker",
            'params' => compact('product_code'),
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

        if (isset($response['error_message'])) {
            return null;
        }

        return (float) $response['ltp'];
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
     * @return array
     */
    public function getLastTradeDataUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $product_code = $first_currency . '_' . $second_currency;

        return [
            'uri' => "executions",
            'params' => compact('product_code'),
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

        if (!$response || isset($response['error_message'])) {
            return null;
        }

        $sum = round($response[0]['price'] * $response[0]['size'], 8);
        $volume = (float) $response[0]['size'];

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
        $product_code = $first_currency . '_' . $second_currency;

        return [
            'uri' => "ticker",
            'params' => compact('product_code'),
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

        if (!$response || isset($response['error_message'])) {
            return null;
        }

        return $response['volume'];
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
        $product_code = $first_currency . '_' . $second_currency;

        return [
            'uri' => "board",
            'params' => compact('product_code'),
        ];
    }

    /**
     * get total demand
     *
     * @param string $response
     * @return float|null
     */
    public function getTotalDemandAndOfferHandle($response)
    {
        $response = json_decode($response, true);

        if (!$response || isset($response['error_message'])) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['asks'] as $ask) {
            $totalDemand += $ask['price'] * $ask['size'];
        }

        $offersAmounts = array_column($response['bids'], 'size');

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }
}