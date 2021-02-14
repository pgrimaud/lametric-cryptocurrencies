<?php

declare(strict_types=1);

namespace Crypto\Helper;

class SymbolHelper
{
    const SYMBOLS = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'JPY' => '¥',
    ];

    /**
     * @param string $code
     *
     * @return string
     */
    public static function getSymbol(string $code): string
    {
        return self::SYMBOLS[$code] ?? '';
    }
}
