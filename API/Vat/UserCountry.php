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

/**
 * Interface used to implement a service that will fetch the current
 * users country.
 */
interface UserCountry
{
    /**
     * Fetch the country for the current user.
     *
     * @return string alpha 2 code for the country.
     */
    public function fetchUsersCountry();
}
