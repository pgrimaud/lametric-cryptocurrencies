<?php
namespace Crypto;

class Response
{
    const PRICE_UP = 'i7465';
    const PRICE_DOWN = 'i7463';

    /**
     * @param array $data
     * @return string
     */
    public function asJson($data = [])
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * @param string $value
     * @return string
     */
    public function error($value = 'INTERNAL ERROR')
    {
        return $this->asJson([
            'frames' => [
                [
                    'index' => 0,
                    'text'  => $value,
                    'icon'  => 'null'
                ]
            ]
        ]);
    }

    /**
     * @param CurrencyCollection $collection
     * @return string
     */
    public function data(CurrencyCollection $collection)
    {
        $frames = [];

        $index = 0;

        /** @var Currency $currency */
        foreach ($collection->getCurrencies() as $currency) {
            $frames[] = [
                'index' => $index,
                'text'  => $currency->getCode(),
                'icon'  => 'null'
            ];

            $index++;

            $frames[] = [
                'index' => $index,
                'text'  => $this->formatPrice($currency->getPrice()) . '$',
                'icon'  => 'null'
            ];

            $index++;

            if ($currency->isShowChange()) {
                $frames[] = [
                    'index' => $index,
                    'text'  => ($currency->getChange() > 0 ? '+' : '') . $currency->getChange() . '%',
                    'icon'  => ($currency->getChange() > 0 ? self::PRICE_UP : self::PRICE_DOWN),
                ];
                $index++;
            }
        }

        return $this->asJson([
            'frames' => $frames
        ]);
    }

    /**
     * @param float $price
     * @return int
     */
    private function formatPrice($price = 0.0)
    {
        if ($price < 10) {
            $fractional = 4;
        } elseif ($price >= 10 && $price < 100) {
            $fractional = 3;
        } elseif ($price >= 100 && $price < 1000) {
            $fractional = 2;
        } elseif ($price >= 1000 && $price < 10000) {
            $fractional = 1;
        } else {
            $fractional = 0;
        }

        return round($price, $fractional);
    }
}
