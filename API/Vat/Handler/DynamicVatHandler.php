<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\Vat\Handler;

/**
 * Interface for DynamicVatHandler.
 *
 * @todo Product category level Vat rules
 */
interface DynamicVatHandler
{
    /**
     * Called by the VatHandler to get the VAT Rate Id that should be used, for the current
     * user.
     *
     * @return int The VAT rate ID that should be used.
     */
    public function getCorrectVatRateId();
}
