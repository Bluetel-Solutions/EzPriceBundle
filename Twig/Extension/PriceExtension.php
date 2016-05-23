<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Twig\Extension;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use EzSystems\EzPriceBundle\API\Price\ContentVatService;
use EzSystems\EzPriceBundle\API\Price\PriceValueWithVatDataCalculator;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat\Gateway\VatIdentifierNotFoundException;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\AutomaticVatHandlerException;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway\VatNotFoundException;
use Psr\Log\LoggerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class PriceExtension extends Twig_Extension
{
    /**
     * @var \EzSystems\EzPriceBundle\API\Price\ContentVatService
     */
    protected $contentVatService;

    /**
     * @var \EzSystems\EzPriceBundle\API\Price\PriceValueWithVatDataCalculator
     */
    protected $calculator;

    /**
     * This property might not be set in the construct!
     *
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        ContentVatService $contentVatService,
        PriceValueWithVatDataCalculator $calculator,
        LoggerInterface $logger = null
    ) {
        $this->contentVatService = $contentVatService;
        $this->calculator = $calculator;
        $this->logger = $logger;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ezprice';
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
                'ezprice_value',
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
     * @return string
     */
    public function priceValue(VersionInfo $versionInfo, Field $price)
    {
        try {
            return $this->calculator->getValueWithVatData(
                $price->value,
                $this->contentVatService->loadVatRateForField(
                    $price->id,
                    $versionInfo->versionNo
                )
            );
        } catch (VatIdentifierNotFoundException $e) {
            if ($this->logger) {
                $this->logger->error("Couldn't find Vat identifier for Field {$price->id} and Version {$versionInfo->versionNo}. Showing base price instead.");
            }
        } catch (AutomaticVatHandlerException $e) {
            if ($this->logger) {
                $this->logger->error("Automatic VAT Handling is selected for Field {$price->id} and Version {$versionInfo->versionNo} but Automatic Vat Handling is not implemented yet. Showing base price instead.");
            }
        } catch (VatNotFoundException $e) {
            if ($this->logger) {
                $this->logger->error("Couldn't find Vat info for Field {$price->id} and Version {$versionInfo->versionNo}. Showing base price instead.");
            }
        }
    }
}
