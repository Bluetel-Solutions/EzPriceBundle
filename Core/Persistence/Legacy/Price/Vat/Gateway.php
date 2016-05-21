<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat;

abstract class Gateway
{
    /**
     * Loads the VAT rate data for $vatRateId.
     *
     * @param mixed $vatRateId
     *
     * @throws \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway\VatNotFoundException
     *                                                                                                 if there is no data for $vatId
     *
     * @return \EzSystems\EzPriceBundle\API\Price\Values\VatRate
     */
    abstract public function getVatRateData($vatRateId);
}
