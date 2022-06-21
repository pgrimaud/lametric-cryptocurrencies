<?php

declare(strict_types=1);

namespace Crypto;

use Crypto\Exception\CryptoNotFoundException;
use Crypto\Exception\CurrencyNotFoundException;
use Predis\Client as PredisClient;

class Price
{
    public function __construct(
        private PredisClient       $predisClient,
        private CurrencyCollection $collection
    )
    {
    }

    public function getValue(string $currencyToShow): void
    {
        $pricesFile = $this->predisClient->get('lametric:cryptocurrencies');
        $prices = json_decode($pricesFile, true);

        /** @var Currency $currency */
        foreach ($this->collection->getCurrencies() as $currency) {
            if (isset($prices[$currency->getCode()])) {
                $currency->setPrice(
                    (float)$prices[$currency->getCode()]['price'] * $this->convertToCurrency($currencyToShow)
                );
                $currency->setChange((float)$prices[$currency->getCode()]['change']);
            } else {
                throw new CryptoNotFoundException($currency->getCode());
            }
        }
    }

    public function getCollection(): CurrencyCollection
    {
        return $this->collection;
    }

    private function convertToCurrency(string $currencyToShow): float|int
    {
        if ($currencyToShow === 'USD') {
            return 1;
        }

        $pricesFile = $this->predisClient->get('lametric:forex');
        $rates = json_decode($pricesFile, true);

        if (!isset($rates[$currencyToShow])) {
            throw new CurrencyNotFoundException(sprintf('Currency %s not found', $currencyToShow));
        }

        return $rates[$currencyToShow];
    }
}
