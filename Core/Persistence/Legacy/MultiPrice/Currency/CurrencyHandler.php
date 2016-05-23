<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency;

use EzSystems\EzPriceBundle\API\MultiPrice\Values\Currency;
use EzSystems\EzPriceBundle\SPI\Persistence\MultiPrice\CurrencyHandler as CurrencyHandlerInterface;

/**
 * Implements the SPI CurrencyHandler interface. Used to retrieve information about
 * a currency.
 */
class CurrencyHandler implements CurrencyHandlerInterface
{
    /**
     * Gateway used to get information about the currency.
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
     * Used to fetch a currency by its code.
     *
     * @param string $code the code to fetch the currency by.
     *
     * @throws CurrencyNotFoundException if no currency is found
     *
     * @return Currency the currency object retrieved.
     */
    public function getCurrencyByCode($code)
    {
        return $this->gateway
                    ->translateDataToCurrency(
                        $this->gateway
                            ->getCurrencyByCode($code)
                    );
    }
}
