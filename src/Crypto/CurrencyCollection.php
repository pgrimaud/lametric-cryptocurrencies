<?php
namespace Crypto;

class CurrencyCollection
{
    /**
     * @var array
     */
    private $currencies = [];

    /**
     * @param Currency $currency
     */
    public function addCurrency(Currency $currency)
    {
        $this->currencies[] = $currency;
    }

    /**
     * @param Currency $currency
     */
    public function removeCurrency(Currency $currency)
    {
        /** @var Currency $cur */
        foreach ($this->currencies as $k => $cur) {
            if ($cur->getCode() === $currency->getCode()) {
                unset($this->currencies[$k]);
            }
        }
    }

    /**
     * @return array
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }
}
