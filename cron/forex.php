<?php

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Predis\Client as PredisClient;

$http = new GuzzleClient();

$allCurrencies = [];

$response = $http->request('GET', 'https://api.exchangerate.host/latest?base=USD');

$rates = json_decode(strval($response->getBody()), true);

$redisKey = 'lametric:forex';

$predis = new PredisClient();

$redisObject = $predis->get($redisKey);

$predis->set($redisKey, json_encode($rates['rates']));
