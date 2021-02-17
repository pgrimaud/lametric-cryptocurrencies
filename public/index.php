<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
$config = require_once __DIR__ . '/../config/parameters.php';

Sentry\init(['dsn' => $config['sentry_key']]);

use Crypto\{Currency, CurrencyCollection};
use Crypto\Exception\{CryptoNotFoundException, NotUpdatedException};
use Crypto\{Price, Response, Validator};

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
    $price->getValue($validator->getData()['currency']);

    echo $response->data($price->getCollection(), $validator->getData()['position'], $validator->getData()['currency']);

} catch (NotUpdatedException $exception) {

    echo $response->error('Please update application!');

} catch (CryptoNotFoundException $exception) {

    $currencyCode = $exception->getMessage();
    echo $response->error('Invalid currency code ' . $currencyCode . '! Please check your configuration!');

} catch (Exception $exception) {

    if (!$exception->getResponse()) {
        echo $response->error();
    }

    $body = json_decode((string) $exception->getResponse()->getBody(true), true);

    if (isset($body['status']['error_code']) && $body['status']['error_code'] === 400) {
        echo $response->error($body['status']['error_message']);
    } else {
        echo $response->error();
    }
}
