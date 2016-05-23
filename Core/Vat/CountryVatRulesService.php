<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Vat;

use EzSystems\EzPriceBundle\API\Vat\CountryVatRulesService as CountryVatRulesServiceInterface;
use EzSystems\EzPriceBundle\API\Vat\Values\CountryVatRule;
use EzSystems\EzPriceBundle\SPI\Persistence\Vat\CountryVatRulesHandler;

/**
 * Service used to fetch vat rules.
 */
class CountryVatRulesService implements CountryVatRulesServiceInterface
{
    /**
     * Country Vat Rules handler, used to fetch vat rules.
     *
     * @var CountryVatRulesHandler
     */
    protected $countryVatRulesHandler;

    /**
     * __construct.
     *
     * @param CountryVatRulesHandler $countryVatRulesHandler
     */
    public function __construct(CountryVatRulesHandler $countryVatRulesHandler)
    {
        $this->countryVatRulesHandler = $countryVatRulesHandler;
    }

    /**
     * Fetch vat rules available for a country.
     *
     * @param string $country The Alpha2 code of the country
     *
     * @return CountryVatRule[]
     */
    public function getVatRulesForCountry($country)
    {
        return $this->countryVatRulesHandler->getVatRulesForCountry($country);
    }

    /**
     * Fetch a vat rule by its ID.
     *
     * @param int $id id to fetch the vat rule by
     *
     * @return CountryVatRule
     */
    public function getVatRuleById($id)
    {
        return $this->countryVatRulesHandler->getVatRuleById($id);
    }
}
