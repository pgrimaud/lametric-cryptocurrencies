<?php
namespace Crypto;

class Response
{
    CONST PRICE_UP   = 'i7465';
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
     * @param Currency $currency
     * @return mixed
     */
    public function data(Currency $currency)
    {
        return $this->asJson([
            'frames' => [
                [
                    'index' => 0,
                    'text'  => $currency->getCode(),
                    'icon'  => 'null'
                ],
                [
                    'index' => 1,
                    'text'  => $currency->getPrice() . '$',
                    'icon'  => 'null'
                ],
                [
                    'index' => 2,
                    'text'  => ($currency->getChange() > 0 ? '+' : '') . $currency->getChange() . '%',
                    'icon'  => ($currency->getChange() > 0 ? self::PRICE_UP : self::PRICE_DOWN),
                ]
            ],
        ]);
    }
}
