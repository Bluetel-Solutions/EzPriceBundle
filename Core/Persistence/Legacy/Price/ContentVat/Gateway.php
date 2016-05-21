<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat;

abstract class Gateway
{
    /**
     * Returns Vat Rate Id associated with $fieldId and $versionNo.
     *
     * @param mixed $fieldId
     * @param int   $versionNo
     *
     * @return int
     */
    abstract public function getVatRateId($fieldId, $versionNo);
}
