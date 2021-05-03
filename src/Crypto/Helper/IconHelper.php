<?php

declare(strict_types=1);

namespace Crypto\Helper;

class IconHelper
{
    const PRICE_UP = 'i7465';
    const PRICE_DOWN = 'i7463';
    const PRICE_NEUTRAL = 'i8432';

    const ICONS = [
        'BTC'   => '857',
        'ETH'   => '44062',
        'XRP'   => '10014',
        'ADA'   => '15385',
        'LTC'   => '17376',
        'NEO'   => '18806',
        'XLM'   => '15391',
        'EOS'   => '15390',
        'IOT'   => '15384',
        'XEM'   => '15393',
        'DASH'  => '10007',
        'XMR'   => '17416',
        'LSK'   => '18805',
        'NANO'  => '18214',
        'ETC'   => '15386',
        'TRX'   => '32698',
        'ZEC'   => '19405',
        'OMG'   => '13656',
        'BNB'   => '43725',
        'XVG'   => '20648',
        'SNT'   => '16931',
        'ETN'   => '22211',
        'LINK'  => '34832',
        'DOT'   => '39813',
        'YFI'   => '40040',
        'YFII'  => '40041',
        'COMP'  => '40042',
        'OXT'   => '40043',
        'UNI'   => '40286',
        'DNT'   => '40958',
        'BAND'  => '40959',
        'CVC'   => '40960',
        'DAI'   => '40961',
        'WBTC'  => '40962',
        'GRO'   => '41367',
        'CEL'   => '42826',
        'FIL'   => '43222',
        'ZRX'   => '43223',
        'SNX'   => '43224',
        'XTZ'   => '32978',
        'CHSB'  => '43303',
        'DOGE'  => '2869',
        'NEXO'  => '43370',
        'BCH'   => '39690',
        'AAVE'  => '43603',
        'ALGO'  => '43605',
        'NU'    => '43607',
        'SUSHI' => '43608',
        'ATOM'  => '43609',
        'GRT'   => '43610',
        'REN'   => '43611',
        '1INCH' => '43662',
        'CAKE'  => '43664',
        'VET'   => '24459',
        'MIOTA' => '15384',
        'BEST'  => '43754',
        'BTT'   => '32980',
        'DGB'   => '43924',
        'TFUEL' => '43930',
        'THETA' => '43931',
        'PI'    => '43932',
        'AKT'   => '44071',
        'FTT'   => '44072',
        'STORJ' => '44097',
        'RVN'   => '24213',
        'PAC'   => '44483',
        'CRO'   => '44358',
        'NMR'   => '44371',
        'BAT'   => '44454',
        'ICX'   => '44490',
        'ONE'   => '44595',
        'SWIRL' => '44651',
        'GROOT' => '44652',
        'RAZOR' => '44653',
        'POODL' => '44718',
        'EGLD'  => '44836',
        'ENJ'   => '43283',
        'CHZ'   => '44908',
        'VTHO'  => '45100',
        'SYL'   => '45109',
        'VGX'   => '45234',
    ];

    /**
     * @param $code
     * @return string|null
     */
    public static function getIcon(string $code): ?string
    {
        return isset(self::ICONS[$code]) ? 'i' . self::ICONS[$code] : null;
    }

    /**
     * @param float $price
     *
     * @return string
     */
    public static function getChangeIcon(float $price): string
    {
        if ($price === 0.0) {
            return self::PRICE_NEUTRAL;
        } elseif ($price > 0) {
            return self::PRICE_UP;
        } else {
            return self::PRICE_DOWN;
        }
    }
}
