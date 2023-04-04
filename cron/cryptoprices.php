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

for ($i = 1; $i <= 2; $i++) {

    echo 'Page ' . $i;

    if (isset($parameters['proxies']) && count($parameters['proxies']) > 0) {
        $totalOfProxies = count($parameters['proxies']);
        $headers = [
            'proxy' => $parameters['proxies'][rand(0, $totalOfProxies - 1)],
            'force_ip_resolve' => 'v4',
        ];
    } else {
        $headers = [];
    }

    try {
        $uri = 'https://api.coincap.io/v2/assets?limit=2000' . ($i > 1 ? '&offset=' . (($i - 1) * 2000) : '');

        $response = $http->request(
            'GET',
            $uri,
            $headers
        );

        $currencies = json_decode(strval($response->getBody()), true);

        foreach ($currencies['data'] as $currency) {

            $symbol = strtoupper($currency['symbol']);
            $price = (float)$currency['priceUsd'];
            $percent = (float)$currency['changePercent24Hr'];

            if (!isset($allCurrencies[$symbol])) {
                $allCurrencies[$symbol] = [
                    'price' => $price,
                    'change' => $percent
                ];
            }
        }

    } catch (ClientException $e) {
        echo ' : ' . $e->getCode();
    }

    echo PHP_EOL;

    // avoid 429 errors
    sleep(1);
}

$predis->set($redisKey, json_encode($allCurrencies));

exit(0);
