<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\eZ\Publish\Core\FieldType\MultiPrice;

use eZ\Publish\Core\FieldType\Tests\FieldTypeTest;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\Price as PriceValueObject;
use EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Type as MultiPriceType;
use EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value as MultiPriceValue;

/**
 * @group fieldType
 * @group ezprice
 * @covers \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\Price\Type
 * @covers \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\Price\Value
 */
class MultiPriceTest extends FieldTypeTest
{
    protected function createFieldTypeUnderTest()
    {
        $fieldType = new MultiPriceType();
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
        return new MultiPriceValue();
    }

    protected function getGBPPriceObject($value)
    {
        return new PriceValueObject(
            [
                'currency_code' => 'GBP',
                'value'         => $value,
                'id'            => 0,
            ]
        );
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
                new MultiPriceValue('foo'),
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ],
            [
                new MultiPriceValue(10.00),
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ],
        ];
    }

    public function provideValidInputForAcceptValue()
    {
        return [
            [
                null,
                new MultiPriceValue(),
            ],
            [
                ['prices' => ['GBP' => $this->getGBPPriceObject(42.23)], 'vatTypeId' => 1],
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(42.23)], 1),
            ],
            [
                ['prices' => ['GBP' => $this->getGBPPriceObject(23)], 'vatTypeId' => 1],
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.)], 1),
            ],
            [
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.42)], 1),
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.42)], 1),
            ],
        ];
    }

    public function provideInputForToHash()
    {
        return [
            [
                new MultiPriceValue(),
                null,
            ],
            [
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.421)], 1, true),
                ['prices' => ['GBP' => ['value' => 23.421, 'currency_code' => 'GBP', 'id' => 0]], 'vatTypeId' => 1,  'isVatIncluded' => true],
            ],
            [
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.422)], 1, false),
                ['prices' => ['GBP' => ['value' => 23.422, 'currency_code' => 'GBP', 'id' => 0]], 'vatTypeId' => 1, 'isVatIncluded' => false],
            ],
            [
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.423)], -1, false),
                ['prices' => ['GBP' => ['value' => 23.423, 'currency_code' => 'GBP', 'id' => 0]], 'vatTypeId' => -1, 'isVatIncluded' => false],
            ],
            [
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.42)], 2, false),
                ['prices' => ['GBP' => ['value' => 23.42, 'currency_code' => 'GBP', 'id' => 0]], 'vatTypeId' => 2, 'isVatIncluded' => false],
            ],
        ];
    }

    public function provideInputForFromHash()
    {
        return [
            [
                null,
                new MultiPriceValue(),
            ],
            [
                ['prices' => ['GBP' => ['value' => 23.421, 'currency_code' => 'GBP', 'id' => 0]], 'vatTypeId' => 1,  'isVatIncluded' => true],
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.421)], 1, true),
            ],
            [
                ['prices' => ['GBP' => ['value' => 23.422, 'currency_code' => 'GBP', 'id' => 0]], 'vatTypeId' => 1, 'isVatIncluded' => false],
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.422)], 1, false),
            ],
            [
                ['prices' => ['GBP' => ['value' => 23.423, 'currency_code' => 'GBP', 'id' => 0]], 'vatTypeId' => -1, 'isVatIncluded' => false],
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.423)], -1, false),
            ],
            [
                ['prices' => ['GBP' => ['value' => 23.42, 'currency_code' => 'GBP', 'id' => 0]], 'vatTypeId' => 2, 'isVatIncluded' => false],
                new MultiPriceValue(['GBP' => $this->getGBPPriceObject(23.42)], 2, false),
            ],
        ];
    }

    protected function provideFieldTypeIdentifier()
    {
        return 'ezmultiprice';
    }

    public function provideDataForGetName()
    {
        return [
            [$this->getEmptyValueExpectation(), ''],
            [new MultiPriceValue([$this->getGBPPriceObject(23.42)], -1), 23.42],
        ];
    }

    public function provideValidDataForValidate()
    {
        return [
            [
                [],
                new MultiPriceValue(7.5),
            ],
        ];
    }
}
