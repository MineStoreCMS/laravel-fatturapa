<?php

namespace Condividendo\FatturaPA\Enums;

use BenSampo\Enum\Enum;

/**
 * @property string $value
 * @method static self N2_2() Non soggette - altri casi
 * @method static self N3_3() Non Imponibile cessioni verso San Marino e Citta del Vaticano
 */
final class RegulatoryReference extends Enum
{
    public const N2_2 = 'Non soggette - altri casi';
    public const N3_3 = 'Non Imponibile cessioni verso San Marino e Citta del Vaticano Art. 71 Dpr 633/72';
}
