<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat;

use EzSystems\EzPriceBundle\API\Price\Values\VatRate;
use EzSystems\EzPriceBundle\API\Vat\Handler\DynamicVatHandler;
use EzSystems\EzPriceBundle\SPI\Persistence\Price\VatHandler as VatHandlerInterface;

class VatHandler implements VatHandlerInterface
{
    /**
     * @var \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway
     */
    protected $gateway;

    /**
     * The dynamic vat handler.
     *
     * @var DynamicVatHandler|null
     */
    protected $dynamicVatHandler;

    /**
     * @param \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway $gateway
     * @param DynamicVatHandler                                                  $dynamicVatHandler The dynamic vat handler to use is the vat type is set to
     *                                                                                              dynamic vat
     */
    public function __construct(Gateway $gateway, DynamicVatHandler $dynamicVatHandler = null)
    {
        $this->gateway = $gateway;
        $this->dynamicVatHandler = $dynamicVatHandler;
    }

    /**
     * Loads the VAT rate data for $vatRateId.
     *
     * @param mixed $vatRateId
     *
     * @throws \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\AutomaticVatHandlerException when automatic VAT is used and there is no dynamic vat handler available.
     *
     * @return \EzSystems\EzPriceBundle\API\Price\Values\VatRate
     */
    public function load($vatRateId)
    {
        if ($vatRateId == -1) {
            if ($this->dynamicVatHandler === null) {
                throw new AutomaticVatHandlerException('No Dynamic vat handler has been provided.');
            } else {
                $vatRateId = $this->dynamicVatHandler->getCorrectVatRateId();
            }
        }

        $vatRateData = $this->gateway->getVatRateData($vatRateId);

        return new VatRate($vatRateData);
    }
}
