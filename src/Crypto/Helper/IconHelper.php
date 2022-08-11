<?php

declare(strict_types=1);

namespace Crypto\Helper;

class IconHelper
{
    const PRICE_UP = 'i48094';
    const PRICE_DOWN = 'i48093';
    const PRICE_NEUTRAL = 'i8432';

    const ICONS = [
        'BTC'       => '857',
        'ETH'       => '44062',
        'XRP'       => '39692',
        'ADA'       => '15385',
        'LTC'       => '17376',
        'NEO'       => '18806',
        'XLM'       => '35142',
        'EOS'       => '15390',
        'IOT'       => '15384',
        'XEM'       => '15393',
        'DASH'      => '10007',
        'XMR'       => '17416',
        'LSK'       => '18805',
        'NANO'      => '18214',
        'ETC'       => '15386',
        'TRX'       => '32698',
        'ZEC'       => '19405',
        'OMG'       => '13656',
        'BNB'       => '46806',
        'XVG'       => '20648',
        'SNT'       => '16931',
        'ETN'       => '22211',
        'LINK'      => '34832',
        'DOT'       => '39813',
        'YFI'       => '40040',
        'YFII'      => '40041',
        'COMP'      => '40042',
        'OXT'       => '40043',
        'UNI'       => '40286',
        'DNT'       => '40958',
        'BAND'      => '40959',
        'CVC'       => '40960',
        'DAI'       => '40961',
        'WBTC'      => '40962',
        'GRO'       => '41367',
        'CEL'       => '42826',
        'FIL'       => '43222',
        'ZRX'       => '43223',
        'SNX'       => '43224',
        'XTZ'       => '32978',
        'CHSB'      => '43303',
        'DOGE'      => '2869',
        'NEXO'      => '43370',
        'BCH'       => '39690',
        'AAVE'      => '43603',
        'ALGO'      => '43605',
        'NU'        => '43607',
        'SUSHI'     => '43608',
        'ATOM'      => '43609',
        'GRT'       => '43610',
        'REN'       => '43611',
        '1INCH'     => '43662',
        'CAKE'      => '43664',
        'VET'       => '24459',
        'MIOTA'     => '15384',
        'BEST'      => '43754',
        'BTT'       => '32980',
        'DGB'       => '43924',
        'TFUEL'     => '43930',
        'THETA'     => '43931',
        'PI'        => '43932',
        'AKT'       => '44071',
        'FTT'       => '45459',
        'STORJ'     => '44097',
        'RVN'       => '24213',
        'PAC'       => '44483',
        'CRO'       => '44358',
        'NMR'       => '44371',
        'BAT'       => '44454',
        'ICX'       => '44490',
        'ONE'       => '44595',
        'SWIRL'     => '44651',
        'GROOT'     => '44652',
        'RAZOR'     => '44653',
        'POODL'     => '44718',
        'EGLD'      => '44836',
        'ENJ'       => '43283',
        'CHZ'       => '44908',
        'VTHO'      => '45100',
        'SYL'       => '45109',
        'VGX'       => '45234',
        'DEV'       => '45281',
        'UMA'       => '45324',
        'BNT'       => '45331',
        'LRC'       => '45326',
        'BUNNY'     => '45376',
        'MATIC'     => '45377',
        'QUICK'     => '45378',
        'VAL'       => '45384',
        'XOR'       => '45385',
        'SRM'       => '45454',
        'SKL'       => '45488',
        'SXP'       => '45514',
        'ICP'       => '45513',
        'FLOW'      => '45475',
        'SOL'       => '45512',
        'BAKE'      => '45516',
        'FET'       => '48097',
        'KNT'       => '45322',
        'HOT'       => '45624',
        'ANT'       => '45332',
        'AVAX'      => '45657',
        'IOST'      => '45656',
        'ZIL'       => '45655',
        'MANA'      => '45654',
        'KNC'       => '45653',
        'KSM'       => '45652',
        'MINA'      => '45662',
        'CELO'      => '45706',
        'TRB'       => '45323',
        'NKN'       => '45720',
        'POOCOIN'   => '45854',
        'LUNA'      => '45994',
        'OGN'       => '45968',
        'RLC'       => '45969',
        'MIR'       => '45971',
        'GLM'       => '45972',
        'CTSI'      => '45996',
        'POLY'      => '45997',
        'CRV'       => '45998',
        'ANKR'      => '46352',
        'POTS'      => '46517',
        'BIFI'      => '46518',
        'DFI'       => '46657',
        'CKB'       => '46791',
        'IOTX'      => '46800',
        'FTM'       => '46811',
        'SPIRIT'    => '46807',
        'FUSE'      => '46812',
        'MOONLIGHT' => '46813',
        'NUTS'      => '46816',
        'BANANA'    => '46817',
        'COTI'      => '46825',
        'AUTO'      => '46879',
        'EDGE'      => '46848',
        'ORN'       => '46937',
        'STAKE'     => '47215',
        'KDA'       => '47258',
        'SHIB'      => '47282',
        'QRDO'      => '47420',
        'HNT'       => '44755',
        'FLUX'      => '47458',
        'RUNE'      => '48095',
        'KRL'       => '48098',
        'RARI'      => '48096',
        'OSMO'      => '48574',
        'JUNO'      => '48777',
        'AXS'       => '48799',
        'SLP'       => '48800',
        'PAN'       => '30988',
        'NEAR'      => '49612',
        'TON'       => '49613',
        'APE'       => '50198',
        'IMX'       => '50199',
    ];

    /**
     * @param string $code
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
