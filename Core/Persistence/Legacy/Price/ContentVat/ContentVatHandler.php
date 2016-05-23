<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat;

use EzSystems\EzPriceBundle\SPI\Persistence\Price\ContentVatHandler as ContentVatHandlerInterface;

class ContentVatHandler implements ContentVatHandlerInterface
{
    /**
     * @var \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat\Gateway
     */
    protected $gateway;

    /**
     * @param \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat\Gateway $gateway
     */
    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Loads the VAT rate for $fieldId in $versionNo.
     *
     * @param mixed $fieldId
     * @param int   $versionNo
     *
     * @throws \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat\Gateway\VatIdentifierNotFoundException
     *                                                                                                                  when the identifier can't be found.
     *
     * @return int
     */
    public function getVatRateIdForField($fieldId, $versionNo)
    {
        return $this->gateway->getVatRateId($fieldId, $versionNo);
    }
}
