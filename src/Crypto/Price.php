<?php

namespace Crypto;

use Crypto\Exception\CryptoNotFoundException;
use GuzzleHttp\Client as GuzzleClient;
use Predis\Client as PredisClient;

class Price
{
    const DATA_ENDPOINT = 'https://web-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?convert=USD&cryptocurrency_type=all&limit=4999';

    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * @var PredisClient
     */
    private $predisClient;

    /**
     * @var Currency
     */
    private $collection;

    /**
     * @param GuzzleClient $guzzleClient
     * @param PredisClient $predisClient
     * @param CurrencyCollection $collection
     */
    public function __construct(GuzzleClient $guzzleClient, PredisClient $predisClient, CurrencyCollection $collection)
    {
        $this->guzzleClient = $guzzleClient;
        $this->predisClient = $predisClient;
        $this->collection   = $collection;
    }

    /**
     * @return void
     * @throws CryptoNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getValue()
    {
        $redisKey = 'lametric:cryptocurrencies';

        $pricesFile = $this->predisClient->get($redisKey);
        $ttl        = $this->predisClient->ttl($redisKey);

        if (!$pricesFile || $ttl < 0) {
            $rawData = $this->fetchData();

            $prices = $this->formatData($rawData);

            // save to redis
            $this->predisClient->set($redisKey, json_encode($prices));
            $this->predisClient->expireat($redisKey, strtotime("+1 minute"));
        } else {
            $prices = json_decode($pricesFile, JSON_OBJECT_AS_ARRAY);
        }

        /** @var Currency $currency */
        foreach ($this->collection->getCurrencies() as $k => $currency) {
            if ($prices[$currency->getCode()]) {
                $currency->setPrice((float)$prices[$currency->getCode()]['price']);
                $currency->setChange((float)$prices[$currency->getCode()]['change']);
            } else {
                throw new CryptoNotFoundException($currency->getCode());
            }
        }
    }

    /**
     * @param $data
     *
     * @return array
     */
    public function formatData($data)
    {
        $formattedData = [];

        foreach ($data as $currency) {
            $formattedData[$currency['short']] = [
                'price'  => $currency['price'],
                'change' => round($currency['change'], 2)
            ];
        }

        return $formattedData;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function fetchData()
    {
        $resource = $this->guzzleClient->request('GET', self::DATA_ENDPOINT);

        $sources = json_decode((string)$resource->getBody(), true);

        $data = [];

        foreach ($sources['data'] as $crypto) {
            // quick fix for DOS multiple currency
            if ($crypto['symbol'] === 'DOS' && $crypto['slug'] === 'demos') {
                continue;
            }

            // quick fix for COMP multiple currency
            if ($crypto['symbol'] === 'COMP' && $crypto['slug'] === 'compound-coin') {
                continue;
            }

            $data[] = [
                'short'  => $crypto['symbol'],
                'price'  => $crypto['quote']['USD']['price'],
                'change' => $crypto['quote']['USD']['percent_change_24h'],
            ];
        }

        return $data;
    }

    /**
     * @return Currency|CurrencyCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
