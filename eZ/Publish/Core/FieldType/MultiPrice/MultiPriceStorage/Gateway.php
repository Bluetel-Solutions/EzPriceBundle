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
use eZ\Publish\SPI\Persistence\Content\Field;

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

    /**
     * Store the additional data for this field.
     *
     * @param Field $field     The field to store the values for
     * @param int   $versionId The ID of the version of the field that we are storing
     *
     * @return bool
     */
    abstract public function storeFieldData(Field $field, $versionInfo);
}
