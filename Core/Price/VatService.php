<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Price;

use EzSystems\EzPriceBundle\API\Price\VatService as VatServiceInterface;
use EzSystems\EzPriceBundle\SPI\Persistence\Price\VatHandler;

class VatService implements VatServiceInterface
{
    /**
     * @var \EzSystems\EzPriceBundle\SPI\Persistence\Price\VatHandler
     */
    protected $vatHandler;

    /**
     * @param \EzSystems\EzPriceBundle\SPI\Persistence\Price\VatHandler $vatHandler
     */
    public function __construct(VatHandler $vatHandler)
    {
        $this->vatHandler = $vatHandler;
    }

    /**
     * Loads the VAT rate data for $vatRateId.
     *
     * @param mixed $vatRateId
     *
     * @return \EzSystems\EzPriceBundle\API\Price\Values\VatRate
     */
    public function loadVatRate($vatRateId)
    {
        return $this->vatHandler->load($vatRateId);
    }
}
