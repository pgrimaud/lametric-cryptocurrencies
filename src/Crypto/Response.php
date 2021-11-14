<?php

declare(strict_types=1);

namespace Crypto;

use Crypto\Helper\IconHelper;
use Crypto\Helper\SymbolHelper;

class Response
{
    const DEFAULT_CRYPTOCURRENCY = 'BTC';

    const POSITION_BEFORE = 'before';
    const POSITION_AFTER = 'after';

    /**
     * @param array $data
     * @return string
     */
    public function asJson(array $data = []): string
    {
        return json_encode($data);
    }

    /**
     * @param string $value
     * @return string
     */
    public function error(string $value = 'INTERNAL ERROR'): string
    {
        return $this->asJson([
            'frames' => [
                [
                    'index' => 0,
                    'text'  => $value,
                    'icon'  => 'null',
                ],
            ],
        ]);
    }

    /**
     * @param CurrencyCollection $collection
     * @param string             $position
     * @param string             $currencyToShow
     *
     * @return string
     */
    public function data(CurrencyCollection $collection, string $position, string $currencyToShow, string|int $format): string
    {
        $frames = [];

        $index = 0;

        /** @var Currency $currency */
        foreach ($collection->getCurrencies() as $currency) {
            if ($currency->showName()) {
                $frames[] = [
                    'index' => $index,
                    'text'  => $currency->getCode(),
                    'icon'  => IconHelper::getIcon($currency->getCode()),
                ];
                $index++;
            }

            $frames[] = [
                'index' => $index,
                'text'  => $this->formatPrice($format, $currency->getPrice(), $position, $currencyToShow),
                'icon'  => IconHelper::getIcon($currency->getCode()),
            ];
            $index++;

            if ($currency->isShowChange()) {
                $frames[] = [
                    'index' => $index,
                    'text'  => ($currency->getChange() > 0 ? '+' : '') . $currency->getChange() . '%',
                    'icon'  => IconHelper::getChangeIcon($currency->getChange()),
                ];
                $index++;
            }
        }

        return $this->asJson([
            'frames' => $frames,
        ]);
    }

    /**
     * @param float  $price
     * @param string $position
     * @param string $currency
     *
     * @return string
     */
    private function formatPrice(string|int $format, float $price = 0.0, string $position = '', string $currency = ''): string
    {
        if ($format === 'default') {
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
        } else {
            $fractional = $format;
        }

        $price = round($price, $fractional);

        // refs to https://github.com/pgrimaud/lametric-cryptocurrencies/issues/14
        $priceExploded = explode('.', (string) $price);
        if ($fractional >= 3 && isset($priceExploded[1]) && strlen($priceExploded[1]) === 1) {
            $price = number_format($price, 2, '.', '');
        }

        // set $ position
        if ($position === self::POSITION_BEFORE) {
            $price = SymbolHelper::getSymbol($currency) . $price;
        } else if ($position === self::POSITION_AFTER) {
            $price = $price . SymbolHelper::getSymbol($currency);
        }

        return (string) $price;
    }
}
