<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice;

use Exception;
use eZ\Publish\Core\FieldType\GatewayBasedStorage;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;

/**
 * Description of MultiPriceStorage.
 *
 * Methods in this interface are called by storage engine.
 *
 * $context array passed to most methods provides some context for the field handler about the
 * currently used storage engine.
 * The array should at least define 2 keys :
 *   - identifier (connection identifier)
 *   - connection (the connection handler)
 * For example, using Legacy storage engine, $context will be:
 *   - identifier = 'LegacyStorage'
 *   - connection = {@link \eZ\Publish\Core\Persistence\Database\DatabaseHandler} object handler (for DB connection),
 *                  to be used accordingly to
 *                  {@link http://incubator.apache.org/zetacomponents/documentation/trunk/Database/tutorial.html ezcDatabase} usage
 *
 * The MulitPrice storage handles the following attributes, following the ezmultiprice field
 * type in eZ Publish 4:
 *  - account_key
 *  - has_stored_login
 *  - is_enabled
 *  - is_locked
 *  - last_visit
 *  - login_count
 */
class MultiPriceStorage extends GatewayBasedStorage
{
    /**
     * Store the field data.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Field $field
     * @param array                                     $context
     *
     * @return null|true
     */
    public function storeFieldData(VersionInfo $versionInfo, Field $field, array $context)
    {
        $gateway = $this->getGateway($context);

        return $gateway->storeFieldData($field, $versionInfo->versionNo);
    }

    /**
     * Add Multiprice information into the externalData property of the field value.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Field $field
     * @param array                                     $context
     */
    public function getFieldData(VersionInfo $versionInfo, Field $field, array $context)
    {
        $gateway = $this->getGateway($context);
        $field->value->externalData = $gateway->getFieldData($field->id, $versionInfo->versionNo);
    }

    /**
     * NOT SUPPORTED - Delete the external information for the MultiPrice field.
     *
     * @param VersionInfo $versionInfo
     * @param array       $fieldIds    Array of field Ids
     * @param array       $context
     *
     * @return bool
     */
    public function deleteFieldData(VersionInfo $versionInfo, array $fieldIds, array $context)
    {
        throw new Exception('Deleting MultiPrice fields is not yet supported.');
    }

    /**
     * Checks if field type has external data to deal with.
     *
     * @return bool
     */
    public function hasFieldData()
    {
        return true;
    }

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\VersionInfo $versionInfo
     * @param \eZ\Publish\SPI\Persistence\Content\Field       $field
     * @param array                                           $context
     *
     * @return \eZ\Publish\SPI\Search\Field[]
     */
    public function getIndexData(VersionInfo $versionInfo, Field $field, array $context)
    {
    }
}
