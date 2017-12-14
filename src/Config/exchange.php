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
        'okex', 'coinnest', 'coinfalcon',
    */

    'available' => [
        'kraken' => [
            'url' => 'https://www.kraken.com',
        ],
        'poloniex' => [
            'url' => 'https://poloniex.com',
        ],
        'bithumb' => [
            'url' => 'https://www.bithumb.com',
        ],
        'bitfinex' => [
            'url' => 'https://www.bitfinex.com',
        ],
        'bitflyer' => [
            'url' => 'https://bitflyer.jp',
        ],
        'okcoin' => [
            'url' => 'https://www.okcoin.com',
        ],
        'bitstamp' => [
            'url' => 'https://www.bitstamp.net',
        ],
        'bittrex' => [
            'url' => 'https://bittrex.com',
        ],
        'gdax' => [
            'url' => 'https://www.gdax.com',
        ],
        'gemini' => [
            'url' => 'https://gemini.com',
        ],
        'hitbtc' => [
            'url' => 'https://hitbtc.com',
        ],
        'korbit' => [
            'url' => 'https://www.korbit.co.kr',
        ],
        'coinone' => [
            'url' => 'https://coinone.co.kr',
        ],
        'binance' => [
            'url' => 'https://www.binance.com',
        ],
        'huobi' => [
            'url' => 'https://www.huobi.pro',
        ],
        'zaif' => [
            'url' => 'https://zaif.jp',
        ],
        'btc38' => [
            'url' => 'http://www.btc38.com',
        ],
        'okcoincn' => [
            'url' => 'https://www.okcoin.cn',
        ],
        'bitconnect' => [
            'url' => 'https://www.bitconnect.co',
        ],
        'livecoin' => [
            'url' => 'https://www.livecoin.net',
        ],
        'novaexchange' => [
            'url' => 'https://novaexchange.com',
        ],
        'allcoin' => [
            'url' => 'https://www.allcoin.com',
        ],
        'bitcoinco' => [
            'url' => 'https://www.bitcoin.co.id',
        ],
        'yobit' => [
            'url' => 'https://yobit.net',
        ],
        'bitz' => [
            'url' => 'https://www.bit-z.com',
        ],
        'bitbay' => [
            'url' => 'https://bitbay.net',
        ],
        'tidex' => [
            'url' => 'https://tidex.com',
        ],
        'btc018' => [
            'url' => 'https://btc018.com',
        ],
        'acx' => [
            'url' => 'https://acx.io',
        ],
        'okex' => [
            'url' => 'https://www.okex.com',
        ],
        'coinnest' => [
            'url' => 'https://www.coinnest.co.kr',
        ],
        'coinfalcon' => [
            'url' => 'https://coinfalcon.com',
        ],
    ],

    'selected' => 'bithumb',

    'default' => 'poloniex',

    /*
     * format:
     * 'exchange' => [
     *     [
     *         first currency,
     *         second currency,
     *         replace currencies(BTC_USD <=> USD_BTC),
     *         convert pair price by second currency('fiat'(KRW, CNY and etc), 'crypto'(BTC, ETH and etc) or false to off converting),
     *         inversely cryptocurrency converting(false - $pairPrice * $secondCurrencyPrice, true - $secondCurrencyPrice / $pairPrice)
     *     ],
     *     ...
     * ]
     * */
    'exchanges_pairs' => [
        'kraken' => [
            ['BTC', 'USD', false, false, false],
            ['ETC', 'USD', false, false, false],
            ['ETH', 'USD', false, false, false],
            ['DASH', 'USD', false, false, false],
            ['LTC', 'USD', false, false, false],
            ['XLM', 'BTC', false, 'crypto', false],
            ['XRP', 'USD', false, false, false],
            ['XMR', 'USD', false, false, false],
            ['ZEC', 'USD', false, false, false],
            ['ZEC', 'EUR', false, 'fiat', false],
            ['ZEC', 'BTC', false, 'crypto', false],
        ],
        'poloniex' => [
            ['BTC', 'USD', false, false, false],
            ['BCH', 'USD', false, false, false],
            ['ETC', 'USD', false, false, false],
            ['ETH', 'USD', false, false, false],
            ['DASH', 'USD', false, false, false],
            ['LTC', 'USD', false, false, false],
            ['XLM', 'USD', false, false, false],
            ['XLM', 'BTC', true, 'crypto', false],
            ['XRP', 'USD', false, false, false],
            ['XMR', 'USD', false, false, false],
            ['STRAT', 'BTC', true, 'crypto', false],
            ['BCN', 'BTC', true, 'crypto', false],
            ['BTS', 'BTC', true, 'crypto', false],
            ['LSK', 'BTC', true, 'crypto', false],
            ['ZEC', 'USD', false, false, false],
            ['ZEC', 'BTC', true, 'crypto', false],
            ['STEEM', 'BTC', true, 'crypto', false],
            ['DCR', 'BTC', true, 'crypto', false],
            ['FCT', 'BTC', true, 'crypto', false],
            ['XEM', 'BTC', true, 'crypto', false],
        ],
        'bithumb' => [
            ['BTC', 'KRW', false, 'fiat', false],
            ['BCH', 'KRW', false, 'fiat', false],
            ['ETH', 'KRW', false, 'fiat', false],
            ['ETC', 'KRW', false, 'fiat', false],
            ['DASH', 'KRW', false, 'fiat', false],
            ['LTC', 'KRW', false, 'fiat', false],
            ['XRP', 'KRW', false, 'fiat', false],
            ['XMR', 'KRW', false, 'fiat', false],
            ['ZEC', 'KRW', false, 'fiat', false],
        ],
        'bitfinex' => [
            ['BTC', 'USD', false, false, false],
            ['BCH', 'USD', false, false, false],
            ['ETH', 'USD', false, false, false],
            ['ETC', 'USD', false, false, false],
            ['DASH', 'USD', false, false, false],
            ['LTC', 'USD', false, false, false],
            ['XMR', 'USD', false, false, false],
            ['IOT', 'USD', false, false, false],
            ['IOT', 'BTC', false, 'crypto', false],
            ['IOT', 'ETH', false, 'crypto', false],
            ['NEO', 'USD', false, false, false],
            ['QTUM', 'USD', false, false, false],
            ['QTUM', 'BTC', false, 'crypto', false],
            ['ZEC', 'USD', false, false, false],
            ['ZEC', 'BTC', false, 'crypto', false],
            ['BTG', 'USD', false, false, false],
            ['BTG', 'BTC', false, 'crypto', false],
        ],
        'bitflyer' => [
            ['BTC', 'USD', false, false, false],
        ],
        'okcoin' => [
            ['BTC', 'USD', false, false, false],
        ],
        'bitstamp' => [
            ['BTC', 'USD', false, false, false],
            ['ETH', 'USD', false, false, false],
            ['LTC', 'USD', false, false, false],
        ],
        'bittrex' => [
            ['BTC', 'USD', false, false, false],
            ['BCH', 'USD', false, false, false],
            ['ETH', 'USD', false, false, false],
            ['ETC', 'USD', false, false, false],
            ['XLM', 'BTC', false, 'crypto', false],
            ['XLM', 'ETH', false, 'crypto', false],
            ['XRP', 'USD', false, false, false],
            ['STRAT', 'BTC', false, 'crypto', false],
            ['WAVES', 'BTC', false, 'crypto', false],
            ['BTS', 'BTC', false, 'crypto', false],
            ['NEO', 'USD', false, false, false],
            ['NEO', 'BTC', false, 'crypto', false],
            ['NEO', 'ETH', false, 'crypto', false],
            ['QTUM', 'BTC', false, 'crypto', false],
            ['QTUM', 'ETH', false, 'crypto', false],
            ['ADA', 'BTC', false, 'crypto', false],
            ['LSK', 'BTC', false, 'crypto', false],
            ['ZEC', 'USD', false, false, false],
            ['ZEC', 'BTC', false, 'crypto', false],
            ['ARK', 'BTC', false, 'crypto', false],
            ['STEEM', 'BTC', false, 'crypto', false],
            ['PIVX', 'BTC', false, 'crypto', false],
            ['KMD', 'BTC', false, 'crypto', false],
            ['DCR', 'BTC', false, 'crypto', false],
            ['FCT', 'BTC', false, 'crypto', false],
            ['XEM', 'BTC', false, 'crypto', false],
            ['BTG', 'USD', false, false, false],
            ['BTG', 'BTC', false, 'crypto', false],
        ],
        'gdax' => [
            ['BTC', 'USD', false, false, false],
            ['ETH', 'USD', false, false, false],
            ['LTC', 'USD', false, false, false],
        ],
        'gemini' => [
            ['BTC', 'USD', false, false, false],
            ['ETH', 'USD', false, false, false],
        ],
        'hitbtc' => [
            ['BTC', 'USD', false, false, false],
            ['BCH', 'USD', false, false, false],
            ['DASH', 'USD', false, false, false],
            ['LTC', 'USD', false, false, false],
            ['XMR', 'USD', false, false, false],
            ['NEO', 'USD', false, false, false],
            ['STRAT', 'BTC', false, 'crypto', false],
            ['BCN', 'USD', false, false, false],
            ['BCN', 'BTC', false, 'crypto', false],
            ['LSK', 'BTC', false, 'crypto', false],
            ['ZEC', 'USD', false, false, false],
            ['ZEC', 'BTC', false, 'crypto', false],
            ['XEM', 'USD', false, false, false],
            ['BTG', 'USD', false, false, false],
            ['BTG', 'BTC', false, 'crypto', false],
        ],
        'korbit' => [
            ['BTC', 'KRW', false, 'fiat', false],
            ['BCH', 'KRW', false, 'fiat', false],
            ['ETC', 'KRW', false, 'fiat', false],
            ['XRP', 'KRW', false, 'fiat', false],
        ],
        'coinone' => [ // TODO брать цены за одни запрос
            ['BTC', 'KRW', false, 'fiat', false],
            ['BCH', 'KRW', false, 'fiat', false],
            ['ETH', 'KRW', false, 'fiat', false],
            ['ETC', 'KRW', false, 'fiat', false],
            ['XRP', 'KRW', false, 'fiat', false],
            ['QTUM', 'KRW', false, 'fiat', false],
            ['IOT', 'KRW', false, 'fiat', false],
        ],
        'binance' => [
            ['BTC', 'USD', false, false, false],
            ['STRAT', 'BTC', false, 'crypto', false],
            ['IOT', 'BTC', false, 'crypto', false],
            ['IOT', 'ETH', false, 'crypto', false],
            ['NEO', 'BTC', false, 'crypto', false],
            ['QTUM', 'BTC', false, 'crypto', false],
            ['BTG', 'BTC', false, 'crypto', false],
        ],
        'huobi' => [
            ['BTC', 'ETH', true, 'crypto', true],
            ['BCH', 'CNY', false, 'fiat', false], // бред отвечает(418 usd) https://be.huobi.com/market/detail?symbol=bcccny
            ['ETH', 'CNY', false, 'fiat', false], // бред отвечает(290 usd) https://be.huobi.com/market/detail?symbol=ethcny
            ['ETC', 'CNY', false, 'fiat', false], // бред отвечает(9 usd) https://be.huobi.com/market/detail?symbol=etccny
            ['LTC', 'BTC', false, 'crypto', false],
        ],
        'zaif' => [
            ['XEM', 'JPY', false, 'fiat', false],
        ],
        'bitconnect' => [
            ['BCC', 'BTC', true, 'crypto', false],
        ],
        'livecoin' => [
            ['BCC', 'BTC', false, 'crypto', false],
            ['LSK', 'BTC', false, 'crypto', false],
            ['FRST', 'BTC', false, 'crypto', false],
        ],
        'novaexchange' => [
            ['BCC', 'BTC', true, 'crypto', false],
        ],
        'allcoin' => [
            ['QTUM', 'BTC', false, 'crypto', false],
            ['HSR', 'BTC', false, 'crypto', false],
        ],
        'bitcoinco' => [
            ['XLM', 'BTC', false, 'crypto', false],
            ['BTG', 'IDR', false, 'fiat', false],
        ],
        'yobit' => [
            ['WAVES', 'BTC', false, 'crypto', false],
            ['LSK', 'BTC', false, 'crypto', false],
            ['BTG', 'BTC', false, 'crypto', false],
        ],
        'bitz' => [
            ['LSK', 'BTC', false, 'crypto', false],
            ['HSR', 'BTC', false, 'crypto', false],
            ['FCT', 'BTC', false, 'crypto', false],
        ],
        'bitbay' => [
            ['LSK', 'PLN', false, 'fiat', false],
        ],
        'tidex' => [
            ['WAVES', 'BTC', false, 'crypto', false],
        ],
        'acx' => [
            ['HSR', 'USD', false, false, false],
        ],
        'okex' => [
            ['BTG', 'BTC', false, 'crypto', false],
            ['IOT', 'BTC', false, 'crypto', false],
        ],
        'coinnest' => [
            ['BTG', 'KRW', false, 'fiat', false],
            ['NEO', 'KRW', false, 'fiat', false],
        ],
        'coinfalcon' => [
            ['IOT', 'BTC', false, 'crypto', false],
        ],
        'okcoincn' => [
            ['BCH', 'CNY', false, 'fiat', false],
            ['ETH', 'CNY', false, 'fiat', false],
            ['ETC', 'CNY', false, 'fiat', false],
            ['LTC', 'CNY', false, 'fiat', false],
        ],
        'btc018' => [
            ['HSR', 'CNY', false, 'fiat', false],
        ],
        'btc38' => [ // ответы пустые
            ['XLM', 'CNY', false, 'fiat', false],
            ['BTS', 'CNY', false, 'fiat', false],
            ['XEM', 'CNY', false, 'fiat', false],
        ],
    ],

    /*
     * exchanges that have endpoint with all pairs prices
     * */
    'exchanges_all_prices' => [
        'bittrex',
        'poloniex',
        'bitfinex',
        'hitbtc',
        'binance',
    ],
];