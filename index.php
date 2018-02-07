<?php
require __DIR__ . '/vendor/autoload.php';

use Crypto\Currency;
use Crypto\Exception\CryptoNotFoundException;
use Crypto\Price;
use Crypto\Response;

header("Content-Type: application/json");

$response = new Response();

try {

    $currency = new Currency($_GET);
    $price    = new Price(new \GuzzleHttp\Client(), new \Predis\Client(), $currency);
    $price->getValue();

    echo $response->data($price->getCurrency());

} Catch (CryptoNotFoundException $exception) {

    echo $response->error('Invalid currency code! Please check your configuration!');

} Catch (Exception $exception) {

    echo $response->error();

}
