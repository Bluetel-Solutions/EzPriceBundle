<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\Price;

interface ContentVatService
{
    /**
     * Returns the vatRate associated with $fieldId and $versionNo.
     *
     * @param $fieldId
     * @param $versionNo
     *
     * @return \EzSystems\EzPriceBundle\API\Price\Values\VatRate
     */
    public function loadVatRateForField($fieldId, $versionNo);
}
