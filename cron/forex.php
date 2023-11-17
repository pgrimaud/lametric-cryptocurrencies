<?php

require __DIR__ . '/../vendor/autoload.php';

$parameters = require_once __DIR__ . '/../config/parameters.php';

use GuzzleHttp\Client as GuzzleClient;
use Predis\Client as PredisClient;

$http = new GuzzleClient();

$allCurrencies = [];

$response = $http->request('GET', 'https://api.freecurrencyapi.com/v1/latest?apikey=' . $parameters['api_key']);

$rates = json_decode(strval($response->getBody()), true);

$redisKey = 'lametric:forex';

$predis = new PredisClient();

$redisObject = $predis->get($redisKey);

$predis->set($redisKey, json_encode($rates['data']));
