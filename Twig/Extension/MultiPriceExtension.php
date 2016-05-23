<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Twig\Extension;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use EzSystems\EzPriceBundle\API\Multi\Values\PriceWithVatData;
use EzSystems\EzPriceBundle\API\MultiPrice\CurrencyService;
use EzSystems\EzPriceBundle\API\MultiPrice\PriceValueWithVatDataCalculator;
use EzSystems\EzPriceBundle\API\Price\ContentVatService;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\AutomaticVatHandlerException;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\CountryVatRuleNotFoundException;
use Psr\Log\LoggerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class MultiPriceExtension extends Twig_Extension
{
    /**
     * @var \EzSystems\EzPriceBundle\API\Price\ContentVatService
     */
    protected $contentVatService;

    /**
     * @var \EzSystems\EzPriceBundle\API\MultiPrice\PriceValueWithVatDataCalculator
     */
    protected $calculator;

    /**
     * Used to determine which currency to use for the current user.
     *
     * @var CurrencyService
     */
    protected $currencyService;

    /**
     * This property might not be set in the construct!
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * __construct.
     *
     * @param ContentVatService               $contentVatService
     * @param PriceValueWithVatDataCalculator $calculator
     * @param CurrencyService                 $currencyService
     * @param LoggerInterface|null            $logger
     */
    public function __construct(
        ContentVatService $contentVatService,
        PriceValueWithVatDataCalculator $calculator,
        CurrencyService $currencyService,
        LoggerInterface $logger = null
    ) {
        $this->contentVatService = $contentVatService;
        $this->calculator = $calculator;
        $this->currencyService = $currencyService;
        $this->logger = $logger;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ezmultiprice';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'ezmultiprice_value',
                array($this, 'priceValue'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * Returns the price associated to the Field $price and Version $versionNo without VAT applied.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\VersionInfo $versionInfo
     * @param \eZ\Publish\API\Repository\Values\Content\Field       $price
     *
     * @return PriceWithVatData
     */
    public function priceValue(VersionInfo $versionInfo, Field $multiPriceValue)
    {
        try {
            // get the currency to use
            $currency = $this->currencyService
                            ->getUsersCurrency();
            // Define the price object for the current currency
            $priceObject = $multiPriceValue->value
                                        ->prices[$currency->code];
            $priceData = $this->calculator->getValueWithVatData(
                                                                $priceObject,
                                                                $this->contentVatService->loadVatRateForField(
                                                                    $multiPriceValue->id,
                                                                    $versionInfo->versionNo
                                                                ),
                                                                $multiPriceValue->value
                                                                                ->isVatIncluded
                                                            );
            // Set the currency variable of the Price Data object returned
            $priceData->setCurrency($currency);

            return $priceData;
        } catch (CountryVatRuleNotFoundException $e) {
            if ($this->logger) {
                $this->logger->error("Couldn't find Vat identifier for Field {$price->id} and Version {$versionInfo->versionNo}. Showing base price instead.");
            }
        } catch (AutomaticVatHandlerException $e) {
            if ($this->logger) {
                $this->logger->error("No Dynamic vat handler has been supplied. Either change the vat type on the field with id {$multiPriceValue->id} or add a dynamic vat handler");
            }
        }
    }
}
