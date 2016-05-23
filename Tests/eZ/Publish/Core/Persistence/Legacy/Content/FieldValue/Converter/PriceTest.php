<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter;

use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\Price as PriceConverter;
use PHPUnit_Framework_TestCase;

/**
 * Test case for Price converter in Legacy storage.
 *
 * @group fieldType
 * @group ezprice
 */
class PriceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\Price
     */
    protected $converter;

    protected function setUp()
    {
        parent::setUp();
        $this->converter = new PriceConverter();
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\Price::toStorageValue
     */
    public function testToStorageValue()
    {
        $price = 3.1415;
        $isVatIncluded = 1;

        $fieldValue = new FieldValue(
            array(
                'data' => array(
                    'price'         => $price,
                    'isVatIncluded' => $isVatIncluded,
                ),
            )
        );

        $storageFieldValue = new StorageFieldValue();

        $this->converter->toStorageValue($fieldValue, $storageFieldValue);

        self::assertEquals($price, $storageFieldValue->dataFloat);
        self::assertEquals('1,1', $storageFieldValue->dataText);
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\Price::toFieldValue
     */
    public function testToFieldValue()
    {
        $isVatIncluded = 1;
        $price = 3.1415;

        $storageFieldValue = new StorageFieldValue(
            array(
                'dataFloat' => $price,
                'dataText'  => "$isVatIncluded,1",
            )
        );

        $fieldValue = new FieldValue();

        $this->converter->toFieldValue($storageFieldValue, $fieldValue);

        self::assertEquals($price, $fieldValue->data['price']);
        self::assertEquals($isVatIncluded, $fieldValue->data['isVatIncluded']);
    }
}
