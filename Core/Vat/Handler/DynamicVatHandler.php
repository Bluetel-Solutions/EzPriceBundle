<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Vat\Handler;

use EzSystems\EzPriceBundle\API\Vat\CountryVatRulesService;
use EzSystems\EzPriceBundle\API\Vat\Handler\DynamicVatHandler as DynamicVatHandlerInterface;
use EzSystems\EzPriceBundle\API\Vat\UserCountry as UserCountryInterface;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\CountryVatRuleNotFoundException;

/**
 * Basic Dynamic vat handler that will retrieve the correct vat rate based on the
 * users country.
 */
class DynamicVatHandler implements DynamicVatHandlerInterface
{
    /**
     * Service used to fetch vat rules for a particular country.
     *
     * @var CountryVatRulesService
     */
    protected $countryVatRulesService;

    /**
     * Service used to fetch the country for the current user.
     *
     * @var UserCountryInterface
     */
    protected $countryService;

    /**
     * The default vat rate to use if we cannot find one for the current country.
     *
     * @var int
     */
    protected $defaultVatRuleId;

    /**
     * __construct.
     *
     * @param CountryVatRulesService $countryVatRulesService
     * @param UserCountryInterface   $countryService
     * @param [type]                 $defaultVatRuleId       The default vat rule id
     */
    public function __construct(
        CountryVatRulesService $countryVatRulesService,
        UserCountryInterface $countryService,
        $defaultVatRuleId
    ) {
        $this->countryVatRulesService = $countryVatRulesService;
        $this->countryService = $countryService;
        $this->defaultVatRuleId = $defaultVatRuleId;
    }

    /**
     * Get the correct vat rate ID for the current user.
     *
     * @return int the id of the vat rate that should be used
     */
    public function getCorrectVatRateId()
    {
        $country = $this->getUsersCountry();

        $prioritisedVatRules = $this->getPrioritisedVatRules(
            $this->getPotentialVatRules($country)
        );

        $topRule = array_shift($prioritisedVatRules);

        return $topRule->vatRateId;
    }

    /**
     * Order the vat rules retrieved by priority. Wildcard is lower priority that normal country.
     *
     * @param CountryVatRule[] $vatRules array of CountryVatRule objects
     *
     * @return CountryVatRule[] in the correct order of priority
     */
    protected function getPrioritisedVatRules($vatRules)
    {
        usort(
            $vatRules,
            function ($a, $b) {
                if ($a->countryCode == $b->countryCode) {
                    return 0;
                }
                if ($b->countryCode == '*') {
                    return -1;
                }

                return 1;
            }
        );

        return $vatRules;
    }

    /**
     * Fetch all of the potential vat rules for the current users country. If no vat rule
     * is found for the users current country then it will fallback to the default
     * vat rate. We should always have a vat rate for * in the vat rules. If that is the case then
     * that rule will be used instead of the default one specified in getDefaultVatRule.
     *
     * @param string $country Alpha 2 code for users country
     *
     * @return CountryVatRule[]
     */
    protected function getPotentialVatRules($country)
    {
        try {
            return $this->countryVatRulesService->getVatRulesForCountry($country);
        } catch (CountryVatRuleNotFoundException $e) {
            return $this->getDefaultVatRules();
        }
    }

    /**
     * Fetch the default vat rule.
     *
     * @return CountryVatRule[]
     */
    protected function getDefaultVatRules()
    {
        $defaultRule = $this->countryVatRulesService
            ->getVatRuleById($this->defaultVatRuleId);

        return array($defaultRule);
    }

    /**
     * Get the users country alpha 2 code from the countryService.
     *
     * @return string Alpha 2 code for the country
     */
    protected function getUsersCountry()
    {
        return $this->countryService
                    ->fetchUsersCountry();
    }
}
