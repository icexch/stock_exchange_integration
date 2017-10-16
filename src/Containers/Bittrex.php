<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Bittrex
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Bittrex extends StockExchange
{
    public $api_uri = 'https://bittrex.com/api/v1.1/public';

    public function getAvailableQuotation()
    {
        return null;
    }

    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        return null;
    }

    /**
     * Used to retrieve the latest trades that have occured for a specific market.
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD')
    {
        $market = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request('getmarkethistory', compact('market'));
        $response = json_decode($responseJSON, true);

        if (!$response || $response['success'] !== true) {
            return null;
        }

        return $response['result'];
    }

    /**
     * Return available coins
     *
     * @return null|array
     */
    public function getAvailableCoins()
    {
        $responseJSON = $this->api_request('getcurrencies');
        $response = json_decode($responseJSON, true);

        if (!$response || $response['success'] !== true) {
            return null;
        }

        $coins = $response['result'];
        $coins = array_filter($coins, function ($item) {
            return $item['IsActive'] === true;
        });

        $coins = array_column($coins, 'Currency');

        return $coins;
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        $market = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => "getticker",
            'params' => compact('market'),
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

        if (!$response || $response['success'] !== true) {
            return null;
        }

        return (float) $response['result']['Last'];
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
        if ($second_currency === 'USD') {
            $second_currency .= 'T';
        }

        if ($first_currency === 'BCH') {
            $first_currency = 'BCC';
        }
        if ($second_currency === 'BCH') {
            $second_currency = 'BCC';
        }

        return $second_currency . '-' . $first_currency;
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
        $market = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'getmarkethistory',
            'params' => compact('market'),
        ];
    }

    /**
     * Get last trade data handle
     *
     * @param string $response
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getLastTradeDataHandle($response, $first_currency = 'BTC', $second_currency = 'USD')
    {
        $response = json_decode($response, true);

        if (!$response || $response['success'] !== true) {
            return null;
        }

        $sum = $response['result'][0]['Total'];
        $volume = $response['result'][0]['Quantity'];

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
        $market = $this->getPair($first_currency, $second_currency);

        return [
            'uri' => 'getmarketsummary',
            'params' => compact('market'),
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

        if (!$response || $response['success'] !== true) {
            return null;
        }

        return $response['result'][0]['Volume'];
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
        $market = $this->getPair($first_currency, $second_currency);
        $type = 'both';

        return [
            'uri' => 'getorderbook',
            'params' => compact('market', 'type'),
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

        if (!$response || $response['success'] !== true) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['result']['buy'] as $ask) {
            $totalDemand += $ask['Rate'] * $ask['Quantity'];
        }

        $offersAmounts = array_column($response['result']['sell'], 'Quantity');

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }
}