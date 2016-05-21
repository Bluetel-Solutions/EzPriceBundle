<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\SPI\Persistence\Vat;

interface CountryVatRulesHandler
{
    /**
     * Get all possible VAT rules for a country.
     *
     * @param string $country The Alpha2 code of the country
     *
     * @throws CountryVatRuleNotFoundException if no vat rate can be found for this country
     *
     * @return CountryVatRule[]
     */
    public function getVatRulesForCountry($country);

    /**
     * Fetch a single vat rule by its ID.
     *
     * @param int $id
     *
     * @throws CountryVatRuleNotFoundException if vat rate if not found
     *
     * @return CountryVatRule
     */
    public function getVatRuleById($id);
}
