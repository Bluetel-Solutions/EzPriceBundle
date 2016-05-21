<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\eZ\Publish\Core\FieldType\Price\PriceTest;

use eZ\Publish\Core\FieldType\Tests\FieldTypeTest;
use EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\Price\Type as PriceType;
use EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\Price\Value as PriceValue;

/**
 * @group fieldType
 * @group ezprice
 * @covers \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\Price\Type
 * @covers \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\Price\Value
 */
class PriceTest extends FieldTypeTest
{
    protected function createFieldTypeUnderTest()
    {
        $fieldType = new PriceType();
        $fieldType->setTransformationProcessor($this->getTransformationProcessorMock());

        return $fieldType;
    }

    protected function getValidatorConfigurationSchemaExpectation()
    {
        return [];
    }

    protected function getSettingsSchemaExpectation()
    {
        return [];
    }

    protected function getEmptyValueExpectation()
    {
        return new PriceValue();
    }

    public function provideInvalidInputForAcceptValue()
    {
        return [
            [
                'foo',
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ],
            [
                [],
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ],
            [
                new PriceValue('foo'),
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ],
        ];
    }

    public function provideValidInputForAcceptValue()
    {
        return [
            [
                null,
                new PriceValue(),
            ],
            [
                42.23,
                new PriceValue(42.23),
            ],
            [
                23,
                new PriceValue(23.),
            ],
            [
                new PriceValue(23.42),
                new PriceValue(23.42),
            ],
        ];
    }

    public function provideInputForToHash()
    {
        return [
            [
                new PriceValue(),
                null,
            ],
            [
                new PriceValue(23.42),
                ['price' => 23.42, 'isVatIncluded' => true],
            ],
            [
                new PriceValue(23.42, false),
                ['price' => 23.42, 'isVatIncluded' => false],
            ],
        ];
    }

    public function provideInputForFromHash()
    {
        return [
            [
                null,
                new PriceValue(),
            ],
            [
                ['price' => 23.42],
                new PriceValue(23.42),
            ],
            [
                ['price' => 23.42, 'isVatIncluded' => false],
                new PriceValue(23.42, false),
            ],
            [
                ['price' => 23.42, 'isVatIncluded' => true],
                new PriceValue(23.42, true),
            ],
            [
                ['price' => 23.42, 'isVatIncluded' => true],
                new PriceValue(23.42, true),
            ],
        ];
    }

    protected function provideFieldTypeIdentifier()
    {
        return 'ezprice';
    }

    public function provideDataForGetName()
    {
        return [
            [$this->getEmptyValueExpectation(), ''],
            [new PriceValue(23.42), '23.42'],
        ];
    }

    public function provideValidDataForValidate()
    {
        return [
            [
                [],
                new PriceValue(7.5),
            ],
        ];
    }
}
