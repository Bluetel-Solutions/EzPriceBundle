<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\MultiPriceStorage;

use eZ\Publish\Core\FieldType\StorageGateway;

abstract class Gateway extends StorageGateway
{
    /**
     * Fetch all multiprice data for a field.
     *
     * @param int $contentAttributeId The attribute ID to fetch the prices of.
     * @param int $versionNo          The version number to fetch the values for.
     *
     * @return array
     */
    abstract public function getFieldData($fieldId, $versionNo);
}
