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
use EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\MultiPrice as MultiPriceConverter;
use PHPUnit_Framework_TestCase;

/**
 * Test case for Price converter in Legacy storage.
 *
 * @group fieldType
 * @group ezprice
 */
class MultiPriceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \EzSystems\EzPriceBundle\eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\MultiPrice
     */
    protected $converter;

    protected function setUp()
    {
        parent::setUp();
        $this->converter = new MultiPriceConverter();
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\MultiPrice::toStorageValue
     */
    public function testToStorageValue()
    {
        $vatRateId = 1;
        $isVatIncluded = 1;

        $fieldValue = new FieldValue(
            array(
                'data' => array(
                    'isVatIncluded' => $isVatIncluded,
                    'vatRateId'     => $vatRateId,
                ),
            )
        );

        $storageFieldValue = new StorageFieldValue();

        $this->converter->toStorageValue($fieldValue, $storageFieldValue);

        self::assertEquals('1,1', $storageFieldValue->dataText);
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\MultiPrice::toFieldValue
     */
    public function testToFieldValue()
    {
        $vatRateId = 2;
        $isVatIncluded = 1;

        $storageFieldValue = new StorageFieldValue(
            array(
                'dataText' => "$vatRateId,$isVatIncluded",
            )
        );

        $fieldValue = new FieldValue();

        $this->converter->toFieldValue($storageFieldValue, $fieldValue);

        self::assertEquals($vatRateId, $fieldValue->data['vatRateId']);
        self::assertEquals($isVatIncluded, $fieldValue->data['isVatIncluded']);
    }
}
