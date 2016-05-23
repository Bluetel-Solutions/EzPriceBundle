<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\Persistence\Vat\CountryVatRules;

use eZ\Publish\Core\Persistence\Legacy\Tests\TestCase;
use EzSystems\EzPriceBundle\API\Vat\Values\CountryVatRule;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\CountryVatRulesHandler;

/**
 * @covers \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\CountryVatRulesHandler
 */
class CountryVatRulesHandlerTest extends TestCase
{
    protected $countryVatRulesGateway;

    public function setup()
    {
        $this->countryVatRulesGateway = $this->getMock(
                'EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\Gateway\DoctrineDatabase',
                array('getVatRulesForCountry', 'getVatRuleById'),
                array($this->getMock('eZ\\Publish\\Core\\Persistence\\Database\\DatabaseHandler'))
            );
    }

    /**
     * @covers \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\CountryVatRulesHandler::GetVatRulesForCountry
     * Make sure that we return rule objects when the gateway returns some rules.
     */
    public function testGetVatRulesForCountryWithRules()
    {
        $this->countryVatRulesGateway
            ->method('getVatRulesForCountry')
            ->will(
                $this->returnValue(
                    array(
                        $this->getTestVatRuleData('GB', 1, 5),
                        $this->getTestVatRuleData('*', 2, 6),
                    )
                )
            );
        $countryVatRulesHandler = new CountryVatRulesHandler($this->countryVatRulesGateway);

        $this->assertEquals(
            $countryVatRulesHandler->getVatRulesForCountry('GB'),
            array(
                $this->getTestVatRule(
                    $this->getTestVatRuleData('GB', 1, 5)
                ),
                $this->getTestVatRule(
                    $this->getTestVatRuleData('*', 2, 6)
                ),
            )
        );
    }

    /**
     * @covers \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\CountryVatRulesHandler::getVatRuleById
     * Make sure that we return rule objects when the gateway returns some rules.
     */
    public function testgetVatRuleById()
    {
        $this->countryVatRulesGateway
            ->method('getVatRuleById')
            ->will(
                $this->returnValue(
                    $this->getTestVatRuleData('GB', 1, 5)
                )
            );
        $countryVatRulesHandler = new CountryVatRulesHandler($this->countryVatRulesGateway);

        $this->assertEquals(
            $countryVatRulesHandler->getVatRuleById('GB'),
            $this->getTestVatRule(
                $this->getTestVatRuleData('GB', 1, 5)
            )
        );
    }

    public function getTestVatRule($data)
    {
        $countryVatRule = new CountryVatRule();
        $countryVatRule->id = $data['id'];
        $countryVatRule->countryCode = $data['country_code'];
        $countryVatRule->vatRateId = $data['vat_type'];

        return $countryVatRule;
    }

    public function getTestVatRuleData($countryCode, $vatRateId, $id)
    {
        return array(
            'country_code' => $countryCode,
            'id'           => $id,
            'vat_type'     => $vatRateId,
        );
    }
}
