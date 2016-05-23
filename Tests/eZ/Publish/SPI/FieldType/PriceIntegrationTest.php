<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\eZ\Publish\SPI\FieldType;

use eZ\Publish\Core\FieldType;
use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Tests\FieldType\BaseIntegrationTest;
use EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\Price\Type as PriceType;
use EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\Price as PriceConverter;

/**
 * @group fieldType
 * @group ezprice
 */
class PriceIntegrationTest extends BaseIntegrationTest
{
    public function getTypeName()
    {
        return 'ezprice';
    }

    public function getCustomHandler()
    {
        $fieldType = new PriceType();
        $fieldType->setTransformationProcessor($this->getTransformationProcessor());

        return $this->getHandler(
            'ezprice',
            $fieldType,
            new PriceConverter(),
            new FieldType\NullStorage()
        );
    }

    /**
     * Returns the FieldTypeConstraints to be used to create a field definition
     * of the FieldType under test.
     *
     * @return \eZ\Publish\SPI\Persistence\Content\FieldTypeConstraints
     */
    public function getTypeConstraints()
    {
        return new Content\FieldTypeConstraints();
    }

    /**
     * Get field definition data values.
     *
     * This is a PHPUnit data provider
     *
     * @return array
     */
    public function getFieldDefinitionData()
    {
        return array(
            array('fieldType', 'ezprice'),
            array('fieldTypeConstraints', new Content\FieldTypeConstraints()),
        );
    }

    /**
     * Get initial field value.
     *
     * @return \eZ\Publish\SPI\Persistence\Content\FieldValue
     */
    public function getInitialValue()
    {
        return new Content\FieldValue(
            array(
                'data'         => array('price' => 42.42, 'isVatIncluded' => true),
                'externalData' => null,
                'sortKey'      => 4242,
            )
        );
    }

    /**
     * Get update field value.
     *
     * Use to update the field
     *
     * @return \eZ\Publish\SPI\Persistence\Content\FieldValue
     */
    public function getUpdatedValue()
    {
        return new Content\FieldValue(
            array(
                'data'         => array('price' => 23.23, 'isVatIncluded' => true),
                'externalData' => null,
                'sortKey'      => 2323,
            )
        );
    }
}
