<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\Core\MultiPrice;

use eZ\Publish\Core\Persistence\Legacy\Tests\TestCase;
use EzSystems\EzPriceBundle\Core\Vat\UserCountry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * @covers UserCountry
 */
class UserCountryTest extends TestCase
{
    /**
     * @covers UserCountry::fetchUsersCountry
     * Test without a session variable so it should return the default country from the _construct
     */
    public function testFetchUsersCountryDefaultCountry()
    {
        $mockSession = new Session(new MockFileSessionStorage());

        $sessionVariableName = 'TestUserCountrySession';

        $defaultCountry = 'GB';

        $currencyService = new UserCountry($mockSession, $defaultCountry, $sessionVariableName);

        $this->assertEquals('GB', $currencyService->fetchUsersCountry());
    }

    /**
     * @covers UserCountry::fetchUsersCountry
     * Test with a session variable so it should return the country from the session
     */
    public function testFetchUsersCountryFromSession()
    {
        $mockSession = new Session(new MockFileSessionStorage());

        $sessionVariableName = 'TestCurrencyServiceCurrency';

        $mockSession->set($sessionVariableName, 'US');

        $defaultCountry = 'GB';

        $currencyService = new UserCountry($mockSession, $defaultCountry, $sessionVariableName);

        $this->assertEquals('US', $currencyService->fetchUsersCountry());
    }
}
