<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules;

/**
 * Abstract gateway that will be extended and used to fetch vat rules.
 */
abstract class Gateway
{
    /**
     * Queries database to get all vat rules for a given country.
     *
     * @param int $country
     *
     * @throws CountryVatRuleNotFoundException if there are no VAT rates available for this country
     *
     * @return array all possible rules that can be applied to the users in the country
     *               specified
     */
    abstract public function getVatRulesForCountry($country);

    /**
     * Fetch a single vat rule by its ID.
     *
     * @param int $id the ID to fetch the vat rule by
     *
     * @throws CountryVatRuleNotFoundException if the vat rule with this ID cannot be found§
     *
     * @return array the data for the vatrule retrieved
     */
    abstract public function getVatRuleById($id);

    /**
     * Used to translate the data retrieved by this gateway into CountryVatRule objects.
     *
     * @param array $data the data to populate the CountryVatRule object with
     *
     * @return CountryVatRule with properties from $data
     */
    abstract public function translateDataToCountryVatRule($data);
}
