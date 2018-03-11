<?php
namespace Crypto;

use Crypto\Exception\CryptoNotFoundException;
use GuzzleHttp\Client as GuzzleClient;
use Predis\Client as PredisClient;

class Price
{
    const DATA_ENDPOINT = 'https://coincap.io/front';

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
     */
    public function getValue()
    {
        $redisKey = 'lametric:cryptocurrencies';

        $pricesFile = $this->predisClient->get($redisKey);

        if (!$pricesFile) {
            $resource = $this->guzzleClient->request('GET', self::DATA_ENDPOINT);
            $file     = $resource->getBody();

            $rawData = json_decode($file, JSON_OBJECT_AS_ARRAY);

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
                $currency->setName($prices[$currency->getCode()]['name']);
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
                'name'   => $currency['long'],
                'price'  => $currency['price'],
                'change' => $currency['cap24hrChange']
            ];
        }

        return $formattedData;
    }

    /**
     * @return Currency|CurrencyCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
