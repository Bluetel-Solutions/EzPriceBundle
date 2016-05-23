<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\Core\MultiPrice;

use EzSystems\EzPriceBundle\API\MultiPrice\Values\Price;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\PriceWithVatData;
use EzSystems\EzPriceBundle\API\Price\Values\VatRate;
use EzSystems\EzPriceBundle\Core\MultiPrice\PriceValueWithVatDataCalculator;
use PHPUnit_Framework_TestCase;

/**
 * @covers \EzSystems\EzPriceBundle\Core\MultiPrice\PriceValueWithVatDataCalculator
 */
class PriceValueWithVatDataCalculatorTest extends PHPUnit_Framework_TestCase
{
    /** @var \EzSystems\EzPriceBundle\Core\MultiPrice\PriceValueWithVatDataCalculator */
    private $calculator;

    public function setUp()
    {
        $this->calculator = new PriceValueWithVatDataCalculator();
    }

    public function testVatDataVatExcluded()
    {
        $price = new Price(
            array(
                'value'         => 180,
                'currency_code' => 'GBP',
                'id'            => 0,
            )
        );

        $vatRate = new VatRate(
            array(
                'percentage' => 17.5,
                'name'       => 'test',
            )
        );

        $priceWithVat = $this->calculator->getValueWithVatData(
                                                                $price,
                                                                $vatRate,
                                                                false
                                                            );

        self::assertEquals(
            new PriceWithVatData(
                array(
                    'isVatIncluded'     => false,
                    'price'             => 180,
                    'priceIncludingVat' => 211.50,
                    'priceExcludingVat' => 180,
                    'vatRate'           => 17.5,
                )
            ),
            $priceWithVat
        );
    }

    public function testVatDataVatIncluded()
    {
        $price = new Price(
            array(
                'value'         => 120,
                'currency_code' => 'GBP',
                'id'            => 0,
            )
        );

        $vatRate = new VatRate(
            array(
                'percentage' => 20,
                'name'       => 'test',
            )
        );

        $priceWithVat = $this->calculator->getValueWithVatData(
                                                                $price,
                                                                $vatRate,
                                                                true
                                                            );

        self::assertEquals(
            new PriceWithVatData(
                array(
                    'isVatIncluded'     => true,
                    'price'             => 120,
                    'priceIncludingVat' => 120.0,
                    'priceExcludingVat' => 100.0,
                    'vatRate'           => 20,
                )
            ),
            $priceWithVat
        );
    }
}
