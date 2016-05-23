<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice;

use eZ\Publish\Core\FieldType\Value as BaseValue;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\Price;

class Value extends BaseValue
{
    /**
     * The prices foreach currency.
     *
     * @var Price[]
     */
    public $prices;

    /**
     * The Id of the vat type that should be used.
     *
     * @var int
     */
    public $vatTypeId;

    /**
     * If VAT is, or not, included in $price {@link $isVatincluded}.
     *
     * @var bool
     */
    public $isVatIncluded = true;

    /**
     * __construct.
     *
     * @param Price[] $prices        Array of price objects for each supported currency
     * @param int     $vatTypeId     The Id of the vat type that should be used.
     * @param bool    $isVatIncluded Whether vat is included or excluded from the base price.
     */
    public function __construct($prices = array(), $vatTypeId = null, $isVatIncluded = true)
    {
        $this->prices = $prices;
        $this->vatTypeId = $vatTypeId;
        $this->isVatIncluded = $isVatIncluded;
    }

    /**
     * Forced to implement this, return nothing.
     *
     * @return string Empty string
     */
    public function __toString()
    {
        return '';
    }
}
