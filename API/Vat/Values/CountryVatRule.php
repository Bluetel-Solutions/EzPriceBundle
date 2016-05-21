<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\Vat\Values;

/**
 * Country VAT Rule object, used to represent vat rule objects.
 */
class CountryVatRule
{
    /**
     * Country code that this VAT rule can be used for.
     * Normally Alpha 2 code but can be a wildcard (*).
     *
     * @var string
     */
    public $countryCode;

    /**
     * Unique Identifier for this VAT rule.
     *
     * @var int
     */
    public $id;

    /**
     * The VAT rate to be applied if this VAT rule is applied.
     *
     * @var int
     */
    public $vatRateId;
}
