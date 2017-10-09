<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Bitstamp
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Bitstamp extends StockExchange
{
    public $api_uri = 'https://www.bitstamp.net/api/v2';

    const TIME_MIN = 'minute';
    const TIME_HOUR = 'hour';
    const TIME_DAY = 'day';

    public function getAvailableQuotation()
    {
        return null;
    }

    /**
     * Get Price Ticker
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return null|array
     */
    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        $symbol = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request('ticker/' . $symbol);
        $response = json_decode($responseJSON, true);

        if (!$response || !is_array($response)) {
            return null;
        }

        return $response;
    }

    /**
     * Get Trade
     *
     * @param string $first_currency
     * @param string $second_currency
     * @param string $time  The time interval from which we want the transactions to be returned.
     *                      Possible values are minute, hour (default) or day.
     * @return array|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD', $time = self::TIME_HOUR)
    {
        $symbol = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request('transactions/' . $symbol . '/?time=' . $time);
        $response = json_decode($responseJSON, true);

        if (!$response || !is_array($response)) {
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
        return [
            'BTC',
            'XRP',
            'LTC',
            'ETH',
        ];
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $symbol = $this->getPair($first_currency, $second_currency);

        return 'ticker/' . $symbol;
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

        if (!$response || !is_array($response)) {
            return null;
        }

        return (float) $response['last'];
    }

    public function getChartData($first_currency = 'BTC', $second_currency = 'USD')
    {
        return null;
    }

    /**
     * get symbol
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    private function getPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        return strtolower($first_currency . $second_currency);
    }
}