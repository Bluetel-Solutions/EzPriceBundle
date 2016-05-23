<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Tests\Twig\Extension;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\Price;
use EzSystems\EzPriceBundle\API\Price\Values\VatRate;
use EzSystems\EzPriceBundle\Core\MultiPrice\CurrencyService;
use EzSystems\EzPriceBundle\Core\MultiPrice\PriceValueWithVatDataCalculator;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency\CurrencyHandler;
use EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value as MultiPriceValue;
use EzSystems\EzPriceBundle\Twig\Extension\MultiPriceExtension;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Twig_Test_IntegrationTestCase;

/**
 * @covers \EzSystems\EzPriceBundle\Twig\Extension\PriceExtension
 */
class MultiPriceExtensionTest extends Twig_Test_IntegrationTestCase
{
    /**
     * @return array
     */
    protected function getExtensions()
    {
        return [
            new MultiPriceExtension(
                $this->getContentVatServiceMock(),
                new PriceValueWithVatDataCalculator(),
                $this->getCurrencyService()
            ),
        ];
    }

    private function getContentVatServiceMock()
    {
        $vatRate = new VatRate(array('percentage' => 10.0, 'name' => 'test'));

        $mock = $this->getMock('EzSystems\EzPriceBundle\API\Price\ContentVatService');
        $mock->expects($this->any())
            ->method('loadVatRateForField')
            ->will($this->returnValue($vatRate));

        return $mock;
    }

    /**
     * @param float $price
     * @param bool  $isVatIncluded
     *
     * @return Field
     */
    protected function createField($prices, $isVatIncluded)
    {
        return new Field(
            array(
                'value' => new MultiPriceValue(
                    $prices,
                    1,
                    $isVatIncluded
                ),
            )
        );
    }

    protected function createPrice($value, $currency, $id)
    {
        return new Price(
            array(
                'value'         => $value,
                'currency_code' => $currency,
                'id'            => $id,
            )
        );
    }

    protected function createVersionInfo()
    {
        return new VersionInfo(array('versionNo' => 1));
    }

    protected function getCurrencyService()
    {
        $currencyGateway = $this->getMock(
                'EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency\Gateway\DoctrineDatabase',
                array('getCurrencyByCode'),
                array($this->getMock('eZ\\Publish\\Core\\Persistence\\Database\\DatabaseHandler'))
            );
        $currencyGateway
            ->method('getCurrencyByCode')
            ->will($this->returnValue($this->getTestCurrencyValues()));

        $currencyHandler = new CurrencyHandler($currencyGateway);

        $mockSession = new Session(new MockFileSessionStorage());

        $sessionVariableName = 'TestCurrencyServiceCurrency';

        $defaultCurrency = 'GBP';

        $currencyService = new CurrencyService($mockSession, $currencyHandler, $defaultCurrency, $sessionVariableName);

        return $currencyService;
    }

    /**
     * @return string
     */
    protected function getFixturesDir()
    {
        return __DIR__.'/_fixtures/ezmultiprice_value';
    }

    protected function getTestCurrencyValues()
    {
        return array(
            'code'   => 'GBP',
            'locale' => 'eng-GB',
            'symbol' => '£',
            'id'     => '0',
        );
    }
}
