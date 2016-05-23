<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\Price\Values;

use EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\Price\Value as PriceValue;

/**
 * A Price\Value with extra information about VAT.
 *
 * @property-read float $priceIncludingVat
 * @property-read float $priceExcludingVat
 * @property-read float $vatRate
 */
class PriceWithVatData extends PriceValue
{
    /** @var float */
    protected $priceIncludingVat;

    /** @var float */
    protected $priceExcludingVat;

    /** @var float */
    protected $vatRate;
}
