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
        return array();
    }

    protected function getSettingsSchemaExpectation()
    {
        return array();
    }

    protected function getEmptyValueExpectation()
    {
        return new PriceValue();
    }

    public function provideInvalidInputForAcceptValue()
    {
        return array(
            array(
                'foo',
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ),
            array(
                array(),
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ),
            array(
                new PriceValue('foo'),
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ),
        );
    }

    public function provideValidInputForAcceptValue()
    {
        return array(
            array(
                null,
                new PriceValue(),
            ),
            array(
                42.23,
                new PriceValue(42.23),
            ),
            array(
                23,
                new PriceValue(23.),
            ),
            array(
                new PriceValue(23.42),
                new PriceValue(23.42),
            ),
        );
    }

    public function provideInputForToHash()
    {
        return array(
            array(
                new PriceValue(),
                null,
            ),
            array(
                new PriceValue(23.42),
                array('price' => 23.42, 'isVatIncluded' => true),
            ),
            array(
                new PriceValue(23.42, false),
                array('price' => 23.42, 'isVatIncluded' => false),
            ),
        );
    }

    public function provideInputForFromHash()
    {
        return array(
            array(
                null,
                new PriceValue(),
            ),
            array(
                array('price' => 23.42),
                new PriceValue(23.42),
            ),
            array(
                array('price' => 23.42, 'isVatIncluded' => false),
                new PriceValue(23.42, false),
            ),
            array(
                array('price' => 23.42, 'isVatIncluded' => true),
                new PriceValue(23.42, true),
            ),
            array(
                array('price' => 23.42, 'isVatIncluded' => true),
                new PriceValue(23.42, true),
            ),
        );
    }

    protected function provideFieldTypeIdentifier()
    {
        return 'ezprice';
    }

    public function provideDataForGetName()
    {
        return array(
            array($this->getEmptyValueExpectation(), ''),
            array(new PriceValue(23.42), '23.42'),
        );
    }

    public function provideValidDataForValidate()
    {
        return array(
            array(
                array(),
                new PriceValue(7.5),
            ),
        );
    }
}
