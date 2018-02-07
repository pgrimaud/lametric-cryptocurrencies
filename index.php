<?php
require __DIR__ . '/vendor/autoload.php';

use Crypto\Price;
use Crypto\Response;

header("Content-Type: application/json");

$response = new Response();

try {

    $currency = new \Crypto\Currency($_GET);
    $price    = new Price(new \GuzzleHttp\Client(), new \Predis\Client(), $currency);
    $price->getValue();

    echo $response->data($price->getCurrency());

} Catch (Exception $exception) {

    echo $response->error();

}
