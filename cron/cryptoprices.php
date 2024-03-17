<?php

require __DIR__ . '/../vendor/autoload.php';

$parameters = require_once __DIR__ . '/../config/parameters.php';

const COINS_TO_NOT_DUPLICATE = [
    'ADA',
    'TAO',
    'SOL',
    'SHIB',
    'CHESS',
    'PRIME',
    'IOT',
    'DOGE'
];

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Predis\Client as PredisClient;

$http = new GuzzleClient();

$redisKey = 'lametric:cryptocurrencies';

$predis = new PredisClient();
$redisObject = $predis->get($redisKey);

$allCurrencies = [];

if (isset($parameters['proxies']) && count($parameters['proxies']) > 0) {
    $totalOfProxies = count($parameters['proxies']);
    $proxy = $parameters['proxies'][rand(0, $totalOfProxies - 1)];

    $options = [
        'proxy' => $proxy,
        'force_ip_resolve' => 'v4',
    ];
} else {
    $options = [];
}

$options = array_merge($options, [
    'headers' => [
        'Accept' => 'application/json',
        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',
    ]
]);

try {
    $response = $http->request(
        'GET',
        'https://api.coinmarketcap.com/data-api/v3/cryptocurrency/listing?start=1&limit=10000&sortBy=market_cap',
        $options
    );

    $currencies = json_decode(strval($response->getBody()), true);

    foreach ($currencies['data']['cryptoCurrencyList'] as $currency) {
        $slug = strtoupper($currency['slug']);
        $symbol = strtoupper($currency['symbol']);
        $price = $currency['quotes'][0]['price'];
        $percent = $currency['quotes'][0]['percentChange24h'];

        if (in_array($currency['symbol'], COINS_TO_NOT_DUPLICATE) && isset($allCurrencies[$currency['symbol']])) {
            continue;
        }

        $allCurrencies[$symbol] = [
            'price' => $price,
            'change' => $percent
        ];

        $allCurrencies[$slug] = [
            'price' => $price,
            'change' => $percent
        ];
    }
    echo date('Y-m-d H:i:s') . ' : ' . $response->getStatusCode() . (isset($proxy) ? ' with proxy ' . $proxy : '');
} catch (ClientException $e) {
    echo date('Y-m-d H:i:s') . ' : ' . $e->getCode() . (isset($proxy) ? ' with proxy ' . $proxy : '');
}

echo PHP_EOL;

$predis->set($redisKey, json_encode($allCurrencies));

exit(0);
