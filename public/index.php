<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
$config = require_once __DIR__ . '/../config/parameters.php';

Sentry\init(['dsn' => $config['sentry_key']]);

use Crypto\Currency;
use Crypto\CurrencyCollection;
use Crypto\Exception\CryptoNotFoundException;
use Crypto\Exception\NotUpdatedException;
use Crypto\Price;
use Crypto\Response;
use Crypto\Validator;

header("Content-Type: application/json");

$response = new Response();

try {

    $validator = new Validator($_GET);
    $validator->check();

    $collection = new CurrencyCollection();

    foreach ($validator->getData()['codes'] as $code) {
        $currency = new Currency();
        $currency->setCode($code);
        $currency->setShowChange($validator->getData()['change']);
        $currency->setName($validator->getData()['names']);

        $collection->addCurrency($currency);
    }

    $price = new Price(new \GuzzleHttp\Client(), new \Predis\Client(), $collection);
    $price->getValue();

    echo $response->data($price->getCollection());

} Catch (NotUpdatedException $exception) {

    echo $response->error('Please update application!');

} Catch (CryptoNotFoundException $exception) {

    $currencyCode = $exception->getMessage();
    echo $response->error('Invalid currency code ' . $currencyCode . '! Please check your configuration!');

} Catch (Exception $exception) {

    echo $response->error();

}
