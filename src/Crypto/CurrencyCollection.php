<?php

namespace Crypto;

class CurrencyCollection
{
    /**
     * @var array
     */
    private array $currencies = [];

    /**
     * @param Currency $currency
     */
    public function addCurrency(Currency $currency): void
    {
        $this->currencies[] = $currency;
    }

    /**
     * @return array
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }
}
