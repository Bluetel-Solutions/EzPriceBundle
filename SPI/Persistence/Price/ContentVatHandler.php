<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\SPI\Persistence\Price;

interface ContentVatHandler
{
    /**
     * Returns the VAT used for $fieldId and $version.
     *
     * @param mixed $fieldId
     * @param int   $versionNo
     *
     * @throws \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\AutomaticVatHandlerException
     *                                                                                                 when automatic VAT is used.
     * @throws \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway\VatNotFoundException
     *                                                                                                 if there is no data for $vatId
     *
     * @return int
     */
    public function getVatRateIdForField($fieldId, $versionNo);
}
