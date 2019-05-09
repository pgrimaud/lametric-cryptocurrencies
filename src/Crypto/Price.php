<?php

namespace Crypto;

use Crypto\Exception\CryptoNotFoundException;
use GuzzleHttp\Client as GuzzleClient;
use Predis\Client as PredisClient;

class Price
{
    const DATA_ENDPOINT = 'https://coinmarketcap.com/all/views/all/';

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
     * Price constructor.
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
            $this->predisClient->expireat($redisKey, strtotime("+3 minutes"));
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
     * @return array
     */
    public function formatData($data)
    {
        $formattedData = [];

        foreach ($data as $currency) {
            $formattedData[$currency['short']] = [
                'price'  => $currency['price'],
                'change' => $currency['change']
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
        $file     = str_replace("\n", '', (string)$resource->getBody());

        preg_match_all('/<tr id=(.*?)col-symbol">(.*?)<\/td>(.*?)class="price" data-usd="(.*?)"(.*?)data-timespan="24h" data-percentusd="(.*?)"/', $file, $out);

        $data = [];

        foreach ($out[2] as $key => $crypto) {
            $data[] = [
                'short'  => $crypto,
                'price'  => number_format($out[4][$key], 10),
                'change' => $out[6][$key],
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
