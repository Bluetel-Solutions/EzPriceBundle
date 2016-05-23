<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter;

use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;

class MultiPrice implements Converter
{
    /**
     * Factory for current class.
     *
     * @note Class should instead be configured as service if it gains dependencies.
     *
     * @return \EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\MultiPrice
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Field value to Storage value.
     *
     * @param FieldValue        $value             the field value to create the storage value from
     * @param StorageFieldValue $storageFieldValue Storage value to populate
     *
     * @return null
     */
    public function toStorageValue(FieldValue $value, StorageFieldValue $storageFieldValue)
    {
        $storageFieldValue->dataText = $value->data['vatRateId'].','.(int) $value->data['isVatIncluded'];
        $storageFieldValue->sortKeyInt = $value->sortKey;
    }

    /**
     * Storage Field value to field value.
     *
     * @param StorageFieldValue $value      the storage value to get the content from
     * @param FieldValue        $fieldValue the field value to populate
     *
     * @return null
     */
    public function toFieldValue(StorageFieldValue $value, FieldValue $fieldValue)
    {
        $fieldValue->data = array();

        if (strstr($value->dataText, ',') !== false) {
            list($vatRateId, $isVatIncluded) = explode(',', $value->dataText);
            $fieldValue->data['vatRateId'] = $vatRateId;
            $fieldValue->data['isVatIncluded'] = ($isVatIncluded == 1) ? true : false;
        }
        $fieldValue->sortKey = $value->sortKeyInt;
    }

    /**
     * @param FieldDefinition        $fieldDef
     * @param StorageFieldDefinition $storageDef
     *
     * @return null
     */
    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef)
    {
    }

    /**
     * @param StorageFieldDefinition $storageDef
     * @param FieldDefinition        $fieldDef
     *
     * @return null
     */
    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef)
    {
    }

    /**
     * @return string
     */
    public function getIndexColumn()
    {
        return 'sort_key_int';
    }
}
