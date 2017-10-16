<?php
return [
    /*
     * Available exchanges
     * 'kraken', 'poloniex', 'bithumb',
     * 'bitfinex', 'bitflyer' ,'okcoin',
     * 'bitstamp', 'bittrex', 'gdax',
     * 'gemini', 'hitbtc', 'korbit',
     * 'coinone', 'binance', 'huobi'
     * 'zaif', 'btc38', 'okcoincn'
    */

    'available' => [
        'kraken', 'poloniex', 'bithumb',
        'bitfinex', 'bitflyer', 'okcoin',
        'bitstamp', 'bittrex', 'gdax',
        'gemini', 'hitbtc', 'korbit',
        'coinone', 'binance', 'huobi',
        'zaif', 'btc38', 'okcoincn'
    ],
	'selected' => 'bithumb',

	'default' => 'poloniex',

    'coinsExchanges' => [
        'BTC' => [
            'bitfinex', 'bithumb', 'bitflyer',
            'okcoin', 'bitstamp', 'bittrex',
            'gdax', 'gemini', 'kraken',
            'poloniex', 'hitbtc', 'korbit',
            'coinone', 'binance',
        ],
        'ETH' => [
            'bitfinex', 'bithumb',
            'okcoincn', 'bitstamp', 'bittrex',
            'gdax', 'gemini', 'kraken',
            'poloniex', 'coinone', 'huobi',
        ],
        'XRP' => [
            'bithumb', 'korbit',
            'bittrex','kraken',
            'poloniex', 'coinone',
        ],
        'BCH' => [
            'bitfinex', 'bithumb',
            'okcoincn', 'bittrex',
            'poloniex', 'hitbtc', 'korbit',
            'coinone', 'huobi',
        ],
        'LTC' => [
            'gdax', 'bithumb', 'bitstamp',
            'okcoincn', 'poloniex', 'hitbtc',
            /*'huobi',*/ 'bitfinex', 'kraken',
        ],
        'DASH' => [
            'bithumb', 'poloniex', 'hitbtc',
            'bitfinex', 'kraken',
        ],
        'XEM' => [
            'zaif', 'hitbtc', 'btc38',
        ],
        'XMR' => [
            'bitfinex', 'hitbtc', 'kraken',
            'poloniex', 'bithumb'
        ],
        'NEO' => [
            'bitfinex', 'bittrex'
        ],
        'ETC' => [
            'okcoincn', 'bithumb', 'coinone',
            'huobi', 'bitfinex', 'poloniex',
            'korbit', 'bittrex', 'kraken',
        ],
    ],

    'coinsConverts' => [
        'BTC' => [
            [
                'exchange' => 'huobi',
                'to'       => 'ETH',
            ],
        ],
        'XEM' => [
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'poloniex',
                'to'       => 'BTC',
                'increase' => true,
            ],
        ],
        'NEO' => [
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bittrex',
                'to'       => 'ETH',
                'change' => true,
            ],
            [
                'exchange' => 'binance',
                'to'       => 'BTC',
                'change' => true,
            ],
        ],
        'LTC' => [
            [
                'exchange' => 'huobi',
                'to'       => 'BTC',
            ],
        ],
    ],
];