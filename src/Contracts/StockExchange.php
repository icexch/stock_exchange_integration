<?php

namespace Warchiefs\StockExchangeIntegration\Contracts;

interface StockExchange
{

	/**
	 * Return information about available pairs
	 * from stock exchange
	 *
	 * @return mixed
	 */
	public function getAvailableQuotation();

	/**
	 * Return detail information about pair
	 *
	 * @param string $first_currency
	 * @param string $second_currency
	 *
	 * @return mixed
	 */
	public function getInfoAboutPair($first_currency = 'BTC', $second_currency = 'USD');

	/**
	 * Return trade history
	 * for currency pair
	 * for timestamps ranges
	 *
	 * @param string $first_currency
	 * @param string $second_currency
	 *
	 * @return mixed
	 */
	public function getTradeHistory($first_currency = 'BTC', $second_currency = 'USD');

    /**
     * Return available coins
     *
     * @return array
     */
    public function getAvailableCoins();

    /**
     * Return avg of pair from all stock exchanges
     *
     * @param $data
     * @param null $convertFiatCallback
     * @param null $convertCryptoCallback
     * @param array $exchangesAllPrices
     * @return float|null
     * @internal param string $first_currency
     * @internal param string $second_currency
     */
    public static function getTickerAverage($data, $convertFiatCallback = null, $convertCryptoCallback = null, $exchangesAllPrices = []);

    /**
     * Return price of pair
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return null|float
     */
    public function getPairPrice($first_currency = 'BTC', $second_currency = 'USD');

    /**
     * Return chart data of currency pair by timestamp range or without it
     *
     * available only on poloniex and kraken
     *
     * data format: [[timestamp, high, low, open, close, advancedData]]
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return null|array
     */
    public function getChartData($first_currency = 'BTC', $second_currency = 'USD');

    /**
     * Get last trade data
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getLastTradeData($first_currency = 'BTC', $second_currency = 'USD');

    /**
     * Get total volume
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return null|float
     */
    public function getTotalVolume($first_currency = 'BTC', $second_currency = 'USD');

    /**
     * Get total demand and offer
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return array
     */
    public function getTotalDemandAndOffer($first_currency = 'BTC', $second_currency = 'USD');
}