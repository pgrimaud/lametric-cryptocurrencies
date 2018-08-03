<?php

namespace Crypto\Helper;

class IconHelper
{
    const PRICE_UP = 'i7465';
    const PRICE_DOWN = 'i7463';

    const ICONS = [
        'BTC'  => '857',
        'ETH'  => '9013',
        'XRP'  => '10014',
        'ADA'  => '15385',
        'LTC'  => '17376',
        'NEO'  => '18806',
        'XLM'  => '15391',
        'EOS'  => '15390',
        'IOT'  => '15384',
        'XEM'  => '15393',
        'DASH' => '10007',
        'XMR'  => '17416',
        'LSK'  => '18805',
        'NANO' => '18214',
        'ETC'  => '15386',
        'TRX'  => '17035',
        'ZEC'  => '19405',
        'OMG'  => '13656',
        'BNB'  => '16699',
        'XVG'  => '20648',
        'SNT'  => '16931',
        'ETN'  => '22211',
    ];

    /**
     * @param $code
     * @return mixed|string
     */
    public static function getIcon($code)
    {
        return isset(self::ICONS[$code]) ? 'i' . self::ICONS[$code] : 'null';
    }
}
