<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Poloniex
 *
 * https://poloniex.com/support/api/
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Poloniex extends StockExchange
{

	/**
	 * Consts for second periods
	 */
	const EVERY_FIVE_MINUTES = 300;
	const EVERY_FIVETEEN_MINUTES = 900;
	const EVERY_HALF_HOUR = 1800;
	const EVERY_TWO_HOURS = 7200;
	const EVERY_FOUR_HOURS = 14400;
	const EVERY_DAY = 86400;

	public $api_uri = 'https://poloniex.com/public';

	/**
	 * Counstructor for Poloniex exchange
	 *
	 * @param       $method
	 * @param array $params
	 *
	 * @return string
	 */
	public function uri_construct($method, array $params = [ ])
	{
		$params['command'] = $method;

		return $this->api_uri.'?'.http_build_query($params);
	}

	/**
	 * Returns the 24-hour volume for all markets,
	 * plus totals for primary currencies. Sample output:
	 *
	 * {"BTC_LTC":{"BTC":"2.23248854","LTC":"87.10381314"},
	 * "BTC_NXT":{"BTC":"0.981616","NXT":"14145"}, ...
	 * "totalBTC":"81.89657704",
	 * "totalLTC":"78.52083806"}
	 *
	 * @return null|array
	 */
	public function getAvailableQuotation()
	{
        $responseJSON = $this->api_request('return24hVolume');
        $response = json_decode($responseJSON, true);

        if (!$response || !is_array($response) || isset($response['error'])) {
            return null;
        }

        return $response;
    }

	/**
	 * Returns the order book for a given market, as well as a sequence number
	 * for use with the Push API and an indicator specifying whether the market is frozen.
	 * You may set currencyPair to "all" to get the order books of all markets.
	 * Sample output:
	 *
	 * {"asks":[[0.00007600,1164],[0.00007620,1300], ... ],
	 * "bids":[[0.00006901,200],[0.00006900,408], ... ],
	 * "isFrozen": 0,
	 * "seq": 18849}
	 *
	 * @param string $first_currency
	 * @param string $second_currency
	 * @param int    $depth
	 *
	 * @return null|array
	 */
	public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'NXT', $depth = 10)
	{
        $pair = $this->getPair($first_currency, $second_currency);

        $responseJSON =  $this->api_request('returnOrderBook', [
			'currencyPair'=> $pair,
			'depth' => $depth
		]);
        $response = json_decode($responseJSON, true);

        if (!$response || !is_array($response) || isset($response['error'])) {
            return null;
        }

        return $response;
	}

    /**
     * Return chart data of currency pair
     *
     * data format: [[timestamp, high, low, open, close, advancedData]]
     *
     * @param string $first_currency
     * @param string $second_currency
     * @param null $start
     * @param int $end
     * @param int $period
     * @return array|null
     */
    public function getChartData($first_currency = 'BTC', $second_currency = 'NXT', $start = null, $end = 9999999999, $period = self::EVERY_FIVE_MINUTES)
	{
        // If start == null, start => day ago
        if(!$start) {
            $start = time() - self::EVERY_DAY;
        }

        $pair = $this->getPair($first_currency, $second_currency);

        $responseJSON = $this->api_request('returnChartData', [
            'currencyPair' => $pair,
            'start' => $start,
            'end' => $end,
            'period' => $period
        ]);
        $response = json_decode($responseJSON, true);

        if (!$response || !is_array($response) || isset($response['error'])) {
            return null;
        }

        $returnData = [];

        foreach ($response as $item) {
            $currentData = [];
            $currentData['timestamp'] = $item['date'];
            $currentData['open'] = $item['open'];
            $currentData['high'] = $item['high'];
            $currentData['low'] = $item['low'];
            $currentData['close'] = $item['close'];
            $currentData['advancedData'] = [
                'weightedAverage' => $item['weightedAverage'],
                'volume' => $item['volume'],
                'quoteVolume' => $item['quoteVolume'],
            ];
            $returnData[] = $currentData;
        }

        return $returnData;
	}

	/**
	 * Returns the past 200 trades for a given market,
	 * or up to 50,000 trades between a range specified in UNIX timestamps
	 * by the "start" and "end" GET parameters.
	 *
	 * Sample output:
	 *
	 * @param string $first_currency
	 * @param string $second_currency
	 * @param null   $start
	 * @param int   $end
	 *
	 * @return null|array
	 */
	public function getTradeHistory($first_currency = 'BTC', $second_currency = 'NXT', $start = null, $end = null)
	{
        $pair = $this->getPair($first_currency, $second_currency);

		if(!$start && !$end) {
            $responseJSON =  $this->api_request('returnTradeHistory', [
				'currencyPair' => $pair,
			]);
		} else {
            $responseJSON = $this->api_request('returnTradeHistory', [
				'currencyPair' => $pair,
				'start' => $start,
				'end' => $end
			]);
		}

        $response = json_decode($responseJSON, true);

        if (!$response || !is_array($response) || isset($response['error'])) {
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
        $responseJSON = $this->api_request('returnCurrencies');
        $response = json_decode($responseJSON, true);

        if (!$response) {
            return null;
        }

        $availableCoins = array_filter($response, function ($item) {
            return !$item['disabled'] && !$item['delisted'];
        });

        return array_keys($availableCoins);
    }

    /**
     * @param string $first_currency
     * @param string $second_currency
     * @return string|array
     */
    public function getPairPriceUrl($first_currency = 'BTC', $second_currency = 'USDT')
    {
        return "returnTicker";
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
        $pair = $this->getPair($first_currency, $second_currency);

        $response = json_decode($response, true);

        if (!isset($response[$pair])) {
            return null;
        }

        return (float) $response[$pair]['last'];
    }

    /**
     * Return pair string
     *
     * change USD to USDT, becouse poloniex don't have USD
     *
     * @param $first_currency
     * @param $second_currency
     * @return string
     */
    private function getPair($first_currency, $second_currency)
    {
        if ($second_currency === 'USD') {
            $second_currency .= 'T';
        }
        if ($second_currency === 'USDT') {
            $pair = $second_currency . '_' . $first_currency;
        } else {
            $pair = $first_currency . '_' . $second_currency;
        }

        return $pair;
    }
}