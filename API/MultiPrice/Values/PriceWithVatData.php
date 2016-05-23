<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\MultiPrice\Values;

use EzSystems\EzPriceBundle\API\Price\Values\PriceWithVatData as StandardPriceWithVatData;

/**
 * Object is an extension of the Price\PriceWithVatData object as we need all of those
 * properties and an additional one for the currency.
 */
class PriceWithVatData extends StandardPriceWithVatData
{
    /**
     * The Currency that is related to this price.
     *
     * @var Currency
     */
    protected $currency;

    /**
     * Set the currency property of this object.
     *
     * @param Currency $currency
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;

        return $this;
    }
}
