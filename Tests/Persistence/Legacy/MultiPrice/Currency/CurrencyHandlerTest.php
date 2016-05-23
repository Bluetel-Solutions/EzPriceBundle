<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\Persistence\MultiPrice\Currency;

use eZ\Publish\Core\Persistence\Legacy\Tests\TestCase;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\Currency;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency\CurrencyHandler;

/**
 * @covers \EzSystems\EzPriceBundle\Core\MultiPrice\CurrencyHandler
 */
class CurrencyHandlerTest extends TestCase
{
    protected $currencyGateway;

    public function setup()
    {
        $currency = $this->getTestCurrencyValues();

        $this->currencyGateway = $this->getMock(
                'EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency\Gateway\DoctrineDatabase',
                array('getCurrencyByCode'),
                array($this->getMock('eZ\\Publish\\Core\\Persistence\\Database\\DatabaseHandler'))
            );
        $this->currencyGateway
            ->method('getCurrencyByCode')
            ->will($this->returnValue($currency));
    }

    /**
     * @covers CurrencyHandler::getCurrencyByCode
     * Make sure the translation function is working as expected.
     */
    public function testGetCurrencyByCode()
    {
        $currency = $this->getTestCurrencyValues();

        $currencyHandler = new CurrencyHandler($this->currencyGateway);

        $this->assertEquals($this->getTestCurrencyObject(), $currencyHandler->getCurrencyByCode('GBP'));
    }

    protected function getTestCurrencyObject()
    {
        $data = $this->getTestCurrencyValues();
        $currency = new Currency();
        $currency->id = $data['id'];
        $currency->code = $data['code'];
        $currency->symbol = $data['symbol'];
        $currency->locale = $data['locale'];

        return $currency;
    }

    protected function getTestCurrencyValues()
    {
        return array(
            'code'   => 'GBP',
            'locale' => 'eng-GB',
            'symbol' => '£',
            'id'     => '0',
        );
    }
}
