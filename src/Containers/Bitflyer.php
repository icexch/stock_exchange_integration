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
}