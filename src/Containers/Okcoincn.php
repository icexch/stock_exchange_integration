<?php

namespace Warchiefs\StockExchangeIntegration\Containers;

/**
 * Class Okcoincn
 *
 * @package Warchiefs\StockExchangeIntegration\Containers
 */
class Okcoincn extends Okcoin
{
    public $api_uri = 'https://www.okcoin.cn/api/v1';
    protected $fiat = 'CNY';
    protected $onlyFiat = true;

    /**
     * get symbol
     *
     * @param string $first_currency
     * @param string $second_currency
     * @return string
     */
    protected function getPair($first_currency = 'BTC', $second_currency = 'CNY')
    {
        if ($first_currency === 'BCH') {
            $first_currency = 'BCC';
        }
        if ($second_currency === 'BCH') {
            $second_currency = 'BCC';
        }

        return strtolower($first_currency . '_CNY');
    }
}