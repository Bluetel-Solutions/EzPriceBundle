<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter;

use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;

class Price implements Converter
{
    /**
     * Factory for current class.
     *
     * @note Class should instead be configured as service if it gains dependencies.
     *
     * @return \EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\Price
     */
    public static function create()
    {
        return new static();
    }

    public function toStorageValue(FieldValue $value, StorageFieldValue $storageFieldValue)
    {
        $storageFieldValue->dataFloat = $value->data['price'];
        $storageFieldValue->dataText = (int) $value->data['isVatIncluded'].',1';
        $storageFieldValue->sortKeyInt = $value->sortKey;
    }

    public function toFieldValue(StorageFieldValue $value, FieldValue $fieldValue)
    {
        $fieldValue->data = array('price' => $value->dataFloat);

        if (strstr($value->dataText, ',') !== false) {
            list($vatRateId, $isVatIncluded) = explode(',', $value->dataText);
            $fieldValue->data['isVatIncluded'] = ($isVatIncluded == 1) ? true : false;
        }
        $fieldValue->sortKey = $value->sortKeyInt;
    }

    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef)
    {
    }

    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef)
    {
    }

    public function getIndexColumn()
    {
        return 'sort_key_int';
    }
}
