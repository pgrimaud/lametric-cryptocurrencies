<?php

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Predis\Client as PredisClient;

$http = new GuzzleClient();

$allCurrencies = [];

for ($i = 1; $i <= 40; $i++) {
    $response = $http->request('GET', 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=USD&per_page=250&page=' . $i);

    $currencies = json_decode(strval($response->getBody()), true);

    foreach ($currencies as $currency) {
        $symbol = strtoupper($currency['symbol']);
        $price = $currency['current_price'];
        $percent = $currency['price_change_percentage_24h'];

        if(!isset($allCurrencies[$symbol])) {
            $allCurrencies[$symbol] = [
                'price' => $price,
                'change' => $percent
            ];
        }
    }

    // avoid 429 errors
    sleep(1);
}

$redisKey = 'lametric:cryptocurrencies';

$predis = new PredisClient();

$redisObject = $predis->get($redisKey);

$predis->set($redisKey, json_encode($allCurrencies));
