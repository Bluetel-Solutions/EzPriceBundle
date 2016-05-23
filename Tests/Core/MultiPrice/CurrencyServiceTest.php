<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\Core\MultiPrice;

use eZ\Publish\Core\Persistence\Legacy\Tests\TestCase;
use EzSystems\EzPriceBundle\Core\MultiPrice\CurrencyService;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency\CurrencyHandler;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency\Gateway\DoctrineDatabase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * @covers \EzSystems\EzPriceBundle\Core\MultiPrice\CurrencyService
 */
class CurrencyServiceTest extends TestCase
{
    protected $currencyGateway;
    protected $currencyHandler;

    public function setup()
    {
        $this->currencyGateway = new DoctrineDatabase($this->getMock('eZ\\Publish\\Core\\Persistence\\Database\\DatabaseHandler'));
        $this->currencyHandler = new CurrencyHandler($this->currencyGateway);
        parent::setUp();
    }

    /**
     * @covers CurrencyService::getUsersCurrency
     * Test without a session variable so it should return the default currency from the _construct
     */
    public function testGetUsersCurrencyDefaultCurrency()
    {
        $mockSession = new Session(new MockFileSessionStorage());

        $sessionVariableName = 'TestCurrencyServiceCurrency';

        $defaultCurrency = 'GBP';

        $currencyService = new CurrencyService($mockSession, $this->currencyHandler, $defaultCurrency, $sessionVariableName);

        $this->assertEquals('GBP', $currencyService->getUsersCurrencyCode());
    }

    /**
     * @covers CurrencyService::getUsersCurrency
     * Test with a session variable so it should return the currency from the session
     */
    public function testGetUsersCurrencyFromSession()
    {
        $mockSession = new Session(new MockFileSessionStorage());

        $sessionVariableName = 'TestCurrencyServiceCurrency';

        $mockSession->set($sessionVariableName, 'EUR');

        $defaultCurrency = 'GBP';

        $currencyService = new CurrencyService($mockSession, $this->currencyHandler, $defaultCurrency, $sessionVariableName);

        $this->assertEquals('EUR', $currencyService->getUsersCurrencyCode());
    }
}
