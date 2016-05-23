<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\SPI\Persistence\MultiPrice;

interface CurrencyHandler
{
    /**
     * Used to fetch a currency by its code.
     *
     * @param string $code the code to fetch the currency by.
     *
     * @throws CurrencyNotFoundException if no currency is found
     *
     * @return Currency the currency object retrieved.
     */
    public function getCurrencyByCode($code);
}
