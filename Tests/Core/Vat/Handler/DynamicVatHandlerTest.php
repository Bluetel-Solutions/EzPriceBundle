<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\Core\MultiPrice;

use eZ\Publish\Core\Persistence\Legacy\Tests\TestCase;
use EzSystems\EzPriceBundle\API\Vat\Values\CountryVatRule;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\CountryVatRuleNotFoundException;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\CountryVatRulesHandler;
use EzSystems\EzPriceBundle\Core\Vat\CountryVatRulesService;
use EzSystems\EzPriceBundle\Core\Vat\Handler\DynamicVatHandler;
use EzSystems\EzPriceBundle\Core\Vat\UserCountry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * @covers DynamicVatHandler
 */
class DynamicVatHandlerTest extends TestCase
{
    /**
     * @covers DynamicVatHandler::getCorrectVatRateId
     * Test that the first matching rule is returned.
     */
    public function testGetCorrectVatRateId()
    {
        $dynamicVatHandler = new DynamicVatHandler(
            $this->getMockCountryVatRulesService(
                array(
                    $this->getTestVatRuleData(
                        'GB',
                        '2',
                        '0'
                    ),
                    $this->getTestVatRuleData(
                        '*',
                        '1',
                        '1'
                    ),
                )
            ),
            $this->getCountryService(),
            1
        );

        $this->assertEquals(
            '2',
            $dynamicVatHandler->getCorrectVatRateId('GB')
        );
    }

    /**
     * @covers DynamicVatHandler::getCorrectVatRateId
     * Test that the rules are priortised correctly, The * rule should not be used.
     */
    public function testGetCorrectVatRateIdPriorityCheck()
    {
        $dynamicVatHandler = new DynamicVatHandler(
            $this->getMockCountryVatRulesService(
                array(
                    $this->getTestVatRuleData(
                        '*',
                        '1',
                        '1'
                    ),
                    $this->getTestVatRuleData(
                        'GB',
                        '2',
                        '0'
                    ),
                )
            ),
            $this->getCountryService(),
            1
        );

        $this->assertEquals(
            '2',
            $dynamicVatHandler->getCorrectVatRateId('GB')
        );
    }

    /**
     * @covers DynamicVatHandler::getCorrectVatRateId
     * Make sure the default rule is returned if we do not find a rule.
     */
    public function testGetCorrectVatRateIdDefaultRule()
    {
        $defaultRuleData = $this->getTestVatRuleData(
                            'GBP',
                            '1',
                            '6'
                        );

        $dynamicVatHandler = new DynamicVatHandler(
            $this->getMockCountryVatRulesServiceEmptyAndDefaultRules($defaultRuleData),
            $this->getCountryService(),
            $defaultRuleData['id']
        );

        $this->assertEquals(
            $defaultRuleData['vat_type'],
            $dynamicVatHandler->getCorrectVatRateId('GB')
        );
    }

    public function getCountryService()
    {
        $mockSession = new Session(new MockFileSessionStorage());

        return new UserCountry($mockSession, 'GB');
    }

    public function getMockCountryVatRulesService($vatRules)
    {
        $countryVatRulesGateway = $this->getMock(
            'EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\Gateway\DoctrineDatabase',
            array('getVatRulesForCountry', 'getVatRuleById'),
            array($this->getMock('eZ\\Publish\\Core\\Persistence\\Database\\DatabaseHandler'))
        );
        $countryVatRulesGateway
            ->method('getVatRulesForCountry')
            ->will(
                $this->returnValue($vatRules)
            );
        $countryVatRulesHandler = new CountryVatRulesHandler($countryVatRulesGateway);

        return new CountryVatRulesService($countryVatRulesHandler);
    }

    public function getMockCountryVatRulesServiceEmptyAndDefaultRules($defaultRule)
    {
        $countryVatRulesGateway = $this->getMock(
            'EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\Gateway\DoctrineDatabase',
            array('getVatRulesForCountry', 'getVatRuleById'),
            array($this->getMock('eZ\\Publish\\Core\\Persistence\\Database\\DatabaseHandler'))
        );
        $countryVatRulesGateway->method('getVatRulesForCountry')
            ->will(
                $this->throwException(new CountryVatRuleNotFoundException('Vat rule not found'))
            );
        $countryVatRulesGateway->method('getVatRuleById')
            ->will(
                $this->returnValue($defaultRule)
            );
        $countryVatRulesHandler = new CountryVatRulesHandler($countryVatRulesGateway);

        return new CountryVatRulesService($countryVatRulesHandler);
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
