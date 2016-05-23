<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\MultiPrice;

use EzSystems\EzPriceBundle\API\MultiPrice\PriceValueWithVatDataCalculator as PriceValueWithVatDataCalculatorInterface;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\Price as PriceValue;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\PriceWithVatData;
use EzSystems\EzPriceBundle\API\Price\Values\VatRate;

/**
 * Creates PriceWithVatData objects based on Price Value + VatRate.
 */
class PriceValueWithVatDataCalculator implements PriceValueWithVatDataCalculatorInterface
{
    /**
     * Returns an object adding the price with and without Vat applied.
     *
     * @param \EzSystems\EzPriceBundle\API\MultiPrice\Values\Price $price
     * @param \EzSystems\EzPriceBundle\API\Price\Values\VatRate    $vatRate
     *
     * @return \EzSystems\EzPriceBundle\API\MultiPrice\Values\PriceWithVatData
     */
    public function getValueWithVatData(PriceValue $price, VatRate $vatRate, $isVatIncluded)
    {
        $priceWithVatInfo = array(
            'price'         => $price->value,
            'isVatIncluded' => $isVatIncluded,
        );

        $vatRatio = 1 + ($vatRate->percentage / 100);
        if ($isVatIncluded) {
            $priceWithVatInfo['priceIncludingVat'] = $price->value;
            $priceWithVatInfo['priceExcludingVat'] = $price->value / $vatRatio;
        } else {
            $priceWithVatInfo['priceExcludingVat'] = $price->value;
            $priceWithVatInfo['priceIncludingVat'] = $price->value * $vatRatio;
        }

        $priceWithVatInfo['vatRate'] = $vatRate->percentage;

        return new PriceWithVatData($priceWithVatInfo);
    }
}
