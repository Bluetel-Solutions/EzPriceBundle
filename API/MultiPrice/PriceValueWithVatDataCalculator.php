<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\MultiPrice;

use EzSystems\EzPriceBundle\API\MultiPrice\Values\Price as PriceValue;
use EzSystems\EzPriceBundle\API\Price\Values\VatRate;

/**
 * Creates PriceWithVatData objects based on Price Value + VatRate + $isVatIncluded.
 */
interface PriceValueWithVatDataCalculator
{
    /**
     * Returns an object adding the price with and without Vat applied.
     *
     * @param PriceValue $price         The price object to evaluate the price of
     * @param VatRate    $vatRate       The vat rate to apply to the price.
     * @param bool       $isVatIncluded whether VAT is included in the price or not.
     *
     * @return \EzSystems\EzPriceBundle\API\Multi\Values\PriceWithVatData
     */
    public function getValueWithVatData(PriceValue $price, VatRate $vatRate, $isVatIncluded);
}
