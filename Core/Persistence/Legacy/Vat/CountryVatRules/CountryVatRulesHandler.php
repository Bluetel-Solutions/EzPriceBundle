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

use EzSystems\EzPriceBundle\API\Vat\Values\CountryVatRule;
use EzSystems\EzPriceBundle\SPI\Persistence\Vat\CountryVatRulesHandler as CountryVatRulesHandlerInterface;

/**
 * Handler used to fetch information about vat rules.
 */
class CountryVatRulesHandler implements CountryVatRulesHandlerInterface
{
    /**
     * The gateway to use to get the vat rules.
     *
     * @var Gateway
     */
    protected $gateway;

    /**
     * __construct.
     *
     * @param Gateway $gateway
     */
    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Get all possible VAT rules for a country.
     *
     * @param string $country The Alpha2 code of the country
     *
     * @throws CountryVatRuleNotFoundException if no vat rate can be found for this country
     *
     * @return CountryVatRule[]
     */
    public function getVatRulesForCountry($country)
    {
        $results = $this->gateway->getVatRulesForCountry($country);
        $data = array();
        foreach ($results as $result) {
            $data[] = $this->gateway->translateDataToCountryVatRule($result);
        }

        return $data;
    }

    /**
     * Fetch a single vat rule by its ID.
     *
     * @param int $id
     *
     * @throws CountryVatRuleNotFoundException if vat rate if not found
     *
     * @return CountryVatRule
     */
    public function getVatRuleById($id)
    {
        $vatRule = $this->gateway->getVatRuleById($id);

        return $this->gateway->translateDataToCountryVatRule($vatRule);
    }
}
