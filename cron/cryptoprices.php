<?php

require __DIR__ . '/../vendor/autoload.php';

$parameters = require_once __DIR__ . '/../config/parameters.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Predis\Client as PredisClient;

$http = new GuzzleClient();

$allCurrencies = [];

$redisKey = 'lametric:cryptocurrencies';

$predis = new PredisClient();
$redisObject = $predis->get($redisKey);

$currencies = $redisObject ? json_decode($redisObject, true) : [];

for ($i = 1; $i <= 10; $i++) {

    echo 'Page ' . $i;

    if (isset($parameters['proxies']) && count($parameters['proxies']) > 0) {
        $totalOfProxies = count($parameters['proxies']);
        $proxy = $parameters['proxies'][rand(0, $totalOfProxies - 1)];

        $headers = [
            'proxy' => $proxy,
            'force_ip_resolve' => 'v4',
        ];
    } else {
        $headers = [];
    }

    try {
        $response = $http->request(
            'GET',
            'https://api.coingecko.com/api/v3/coins/markets?vs_currency=USD&per_page=250&page=' . $i,
            $headers
        );

        $currencies = json_decode(strval($response->getBody()), true);

        foreach ($currencies as $currency) {
            $symbol = strtoupper($currency['symbol']);
            $price = $currency['current_price'];
            $percent = $currency['price_change_percentage_24h'];

            if (!isset($allCurrencies[$symbol])) {
                $allCurrencies[$symbol] = [
                    'price' => $price,
                    'change' => $percent
                ];
            }
        }

    } catch (ClientException $e) {
        echo ' : ' . $e->getCode() . ' with proxy ' . $proxy;
    }

    echo PHP_EOL;

    // avoid 429 errors
    sleep(1);
}

$predis->set($redisKey, json_encode($allCurrencies));

exit(0);
