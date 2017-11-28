<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Hitbtc
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Hitbtc extends StockExchange
{
    public $api_uri = 'http://api.hitbtc.com/api/1/public';

    public function getAvailableQuotation()
    {
        return null;
    }

    public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD')
    {
        return null;
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @param int $from
     * @param string $by
     * @param int $start_index
     * @return array|null
     */
    public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD', $from = 0, $by = 'timestamp', $start_index = 0)
    {
        $data = [];
        $data['from'] = $from;
        $data['by'] = $by;
        $data['start_index'] = $start_index;
        $data['format_item'] = 'object';
        $data['sort'] = 'desc';

        $pair = $this->getPair($first_currency, $second_currency);
        $responseJSON = $this->api_request("{$pair}/trades", $data);
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['error'])) {
            return null;
        }

        return $response;
    }

    /**
     * Return available coins
     *
     * @return null|array
     */
    public function getAvailableCoins()
    {
        $responseJSON = $this->api_request("symbols");
        $response = json_decode($responseJSON, true);

        if (!$response || isset($response['error'])) {
            return null;
        }

        $coins = [];

        foreach ($response['symbols'] as $symbol) {
            if (in_array($symbol['currency'], $coins)) {
                continue;
            }
            $coins[] = $symbol['currency'];
            if (in_array($symbol['commodity'], $coins)) {
                continue;
            }
            $coins[] = $symbol['commodity'];
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
        $pair = $this->getPair($first_currency, $second_currency);

        return "{$pair}/ticker";
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

        if (!$response || isset($response['error'])) {
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
        return $first_currency . $second_currency;
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
        $pair = $this->getPair($first_currency, $second_currency);

        $data = [];
        $data['from'] = 0;
        $data['by'] = 'timestamp';
        $data['start_index'] = 0;
        $data['format_item'] = 'object';
        $data['sort'] = 'desc';

        return [
            'uri' => "{$pair}/trades",
            'params' => $data,
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

        if (!$response || isset($response['error'])) {
            return null;
        }

        $sum = round($response['trades'][0]['price'] * $response['trades'][0]['amount'], 8);
        $volume = (float) $response['trades'][0]['amount'];

        return compact('sum', 'volume');
    }

    /**
     * Get total volume url
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return null|float
     */
    public function getTotalVolumeUrl($first_currency = 'BTC', $second_currency = 'USD')
    {
        $pair = $this->getPair($first_currency, $second_currency);

        return "{$pair}/ticker";
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

        if (!$response || isset($response['error'])) {
            return null;
        }

        return (float) $response['volume_quote'];
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
        $pair = $this->getPair($first_currency, $second_currency);

        return "{$pair}/orderbook";
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

        if (!$response || isset($response['error'])) {
            return null;
        }

        $totalDemand = 0;

        foreach ($response['asks'] as $ask) {
            $totalDemand += $ask[0] * $ask[1];
        }

        $offersAmounts = array_column($response['bids'], 1);

        $totalOffer = array_sum($offersAmounts);

        return compact('totalDemand', 'totalOffer');
    }
}