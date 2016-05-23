<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\Vat;

use EzSystems\EzPriceBundle\API\Vat\Values\CountryVatRule;

/**
 * Interface used to to implement a service which fetches VAT rules
 * for a country or by ID.
 */
interface CountryVatRulesService
{
    /**
     * Fetch all VAT rules that can be applied to the users current country.
     *
     * @param string $country The Alpha2 code of the country
     *
     * @return CountryVatRule[]
     */
    public function getVatRulesForCountry($country);

    /**
     * Fetch a single vat rule by its ID.
     *
     * @param int $id the id to fetch the vat rule by
     *
     * @return CountryVatRule the CountryVatRule fetched from the id supplied
     */
    public function getVatRuleById($id);
}
