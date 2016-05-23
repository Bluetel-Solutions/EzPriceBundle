<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\MultiPrice;

/**
 * Interface for the service used to fetch information about the current
 * users currency.
 */
interface CurrencyService
{
    /**
     * Get the currency for the current user.
     *
     * @return string the current users currency code.
     */
    public function getUsersCurrencyCode();

    /**
     * Fetch the users current currency object.
     *
     * @return Values\Currency The currency object that should be
     *                         applied to prices for the current user.
     */
    public function getUsersCurrency();
}
