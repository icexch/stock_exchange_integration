<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Bitfinex
 *
 * https://api.bithumb.com/public/
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Bitfinex extends StockExchange
{

	public $api_uri = 'https://api.bitfinex.com/v1';


    /**
     * Get a list of valid symbol IDs and the pair details.
     *
     * @return array|null
     */
    public function getAvailableQuotation()
	{
        $responseJSON = $this->api_request('symbols_details');
        $response = json_decode($responseJSON, true);

        if (isset($response['message'])) {
            return null;
        }

        return $response;
    }

    /**
     * Current ticker info
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array|null
     */
    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
	{
        $pair = $this->getPair($first_currency . $second_currency);

        $responseJSON = $this->api_request('pubticker/' . $pair);
        $response = json_decode($responseJSON, true);

        if (isset($response['message'])) {
            return null;
        }

        return $response;
	}

	public function getChartData($first_currency = 'BTC', $second_currency = 'NXT')
	{
        return null;
	}

    /**
     * Get a list of the most recent trades for the given pair.
     *
     * @param string $first_currency
     * @param string $second_currency
     * @param null $start
     * @param null $limit
     * @return array|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD', $start = null, $limit = null)
	{
        $pair = $this->getPair($first_currency, $second_currency);

        $params = [];

        if ($start) {
            $params['start'] = $start;
        }

        if ($limit) {
            $params['limit_trades'] = $limit;
        }

        $responseJSON =  $this->api_request('trades/' . $pair, $params);
        $response = json_decode($responseJSON, true);

        if (isset($response['message'])) {
            return null;
        }

        return $response;
	}

    /**
     * Return available coins
     *
     * not recommended to use this
     *
     * @return array
     */
    public function getAvailableCoins()
    {
        $responseJSON = $this->api_request('symbols');
        $response = json_decode($responseJSON, true);
        $coins = [];

        foreach ($response as $pair) {
            if (strlen($pair) > 6) {
                return [];
            }
            $first = substr($pair, 0, 3);
            $second = substr($pair, 3, 3);
            if (!in_array($first, $coins)) {
                $coins[] = $first;
            }
            if (!in_array($second, $coins)) {
                $coins[] = $second;
            }
        }

        return $coins;
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string
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

        if (isset($response['message'])) {
            return null;
        }

        return (float) $response['last_price'];
    }

    private function getPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        if ($first_currency === 'DASH') {
            $first_currency = 'dsh';
        }
        if ($second_currency === 'DASH') {
            $second_currency = 'dsh';
        }

        return strtolower($first_currency . $second_currency);
    }
}