<?php
return [
    /*
     * Available exchanges
        'kraken', 'poloniex', 'bithumb',
        'bitfinex', 'bitflyer', 'okcoin',
        'bitstamp', 'bittrex', 'gdax',
        'gemini', 'hitbtc', 'korbit',
        'coinone', 'binance', 'huobi',
        'zaif', 'btc38', 'okcoincn',
        'bitconnect', 'livecoin', 'novaexchange',
        'allcoin', 'bitcoinco', 'yobit', 'bitz',
        'bitbay', 'tidex', 'btc018', 'acx',
    */

    'available' => [
        'kraken', 'poloniex', 'bithumb',
        'bitfinex', 'bitflyer', 'okcoin',
        'bitstamp', 'bittrex', 'gdax',
        'gemini', 'hitbtc', 'korbit',
        'coinone', 'binance', 'huobi',
        'zaif', 'btc38', 'okcoincn',
        'bitconnect', 'livecoin', 'novaexchange',
        'allcoin', 'bitcoinco', 'yobit', 'bitz',
        'bitbay', 'tidex', 'btc018', 'acx',
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
        'IOT' => [
            'bitfinex',
        ],
        'QTUM' => [
            'coinone', 'bitfinex',
        ],
        'XLM' => [
            'poloniex', 'btc38',
        ],
        'ZEC' => [
            'hitbtc', 'bithumb', 'bitfinex',
            'poloniex', 'bittrex', 'kraken',
        ],
        'HSR' => [
            'btc018', 'acx',
        ],
        'BCN' => [
            'hitbtc',
        ],
        'BTS' => [
            'btc38',
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
        'BCC' => [
            [
                'exchange' => 'bitconnect',
                'to'       => 'BTC',
                'from_icex' => true,
                'increase' => true,
            ],
            [
                'exchange' => 'livecoin',
                'to'       => 'BTC',
                'from_icex' => true,
                'change' => true,
            ],
            [
                'exchange' => 'novaexchange',
                'to'       => 'BTC',
                'from_icex' => true,
                'increase' => true,
            ],
        ],
        'IOT' => [
            [
                'exchange' => 'bitfinex',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bitfinex',
                'to'       => 'ETH',
                'change' => true,
            ],
            [
                'exchange' => 'binance',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'binance',
                'to'       => 'ETH',
                'change' => true,
            ],
        ],
        'QTUM' => [
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
            [
                'exchange' => 'allcoin',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bitfinex',
                'to'       => 'BTC',
                'change' => true,
            ],
        ],
        'ADA' => [
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
        ],
        'XLM' => [
            [
                'exchange' => 'poloniex',
                'to'       => 'BTC',
                'increase' => true,
            ],
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
                'exchange' => 'kraken',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bitcoinco',
                'to'       => 'BTC',
                'change' => true,
                'from_icex' => true,
            ],
        ],
        'LSK' => [
            [
                'exchange' => 'yobit',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'poloniex',
                'to'       => 'BTC',
                'increase' => true,
            ],
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bitz',
                'to'       => 'BTC',
                'change' => true,
                'from_icex' => true,
            ],
            [
                'exchange' => 'livecoin',
                'to'       => 'BTC',
                'change' => true,
                'from_icex' => true,
            ],
            [
                'exchange' => 'hitbtc',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bitbay',
                'to'       => 'PLN',
                'fiat' => true,
                'change' => true,
            ],
        ],
        'ZEC' => [
            [
                'exchange' => 'hitbtc',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bitfinex',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'poloniex',
                'to'       => 'BTC',
                'increase' => true,
            ],
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'kraken',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'kraken',
                'to'       => 'EUR',
                'fiat' => true,
                'change' => true,
            ],
        ],
        'WAVES' => [
            [
                'exchange' => 'tidex',
                'to'       => 'BTC',
                'change' => true,
                'from_icex' => true,
            ],
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'yobit',
                'to'       => 'BTC',
                'change' => true,
            ],
        ],
        'HSR' => [
            [
                'exchange' => 'allcoin',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bitz',
                'to'       => 'BTC',
                'change' => true,
                'from_icex' => true,
            ],
        ],
        'STRAT' => [
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
            [
                'exchange' => 'binance',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'hitbtc',
                'to'       => 'BTC',
                'change' => true,
            ],
        ],
        'ARK' => [
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
        ],
        'STEEM' => [
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
        'BCN' => [
            [
                'exchange' => 'hitbtc',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'poloniex',
                'to'       => 'BTC',
                'increase' => true,
            ],
        ],
        'PIVX' => [
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
        ],
        'KMD' => [
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
        ],
        'FRST' => [
            [
                'exchange' => 'livecoin',
                'to'       => 'BTC',
                'change' => true,
                'from_icex' => true,
            ],
        ],
        'DCR' => [
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
        'FCT' => [
            [
                'exchange' => 'bittrex',
                'to'       => 'BTC',
                'change' => true,
            ],
            [
                'exchange' => 'bitz',
                'to'       => 'BTC',
                'change' => true,
                'from_icex' => true,
            ],
            [
                'exchange' => 'poloniex',
                'to'       => 'BTC',
                'increase' => true,
            ],
        ],
        'BTS' => [
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
    ],
];