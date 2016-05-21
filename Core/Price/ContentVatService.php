<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Price;

use EzSystems\EzPriceBundle\API\Price\ContentVatService as ContentVatServiceInterface;
use EzSystems\EzPriceBundle\API\Price\VatService;
use EzSystems\EzPriceBundle\SPI\Persistence\Price\ContentVatHandler;

class ContentVatService implements ContentVatServiceInterface
{
    /**
     * @var \EzSystems\EzPriceBundle\SPI\Persistence\Price\ContentVatHandler
     */
    protected $contentVatHandler;

    /**
     * @var \EzSystems\EzPriceBundle\API\Price\VatService;
     */
    protected $vatService;

    /**
     * @param \EzSystems\EzPriceBundle\SPI\Persistence\Price\ContentVatHandler $contentVatHandler
     * @param \EzSystems\EzPriceBundle\API\Price\VatService                    $vatService;
     */
    public function __construct(ContentVatHandler $contentVatHandler, VatService $vatService)
    {
        $this->contentVatHandler = $contentVatHandler;
        $this->vatService = $vatService;
    }

    /**
     * Returns the vatRate associated with $fieldId and $versionNo.
     *
     * @param $fieldId
     * @param $versionNo
     *
     * @return \EzSystems\EzPriceBundle\API\Price\Values\VatRate
     */
    public function loadVatRateForField($fieldId, $versionNo)
    {
        return $this->vatService->loadVatRate(
            $this->contentVatHandler->getVatRateIdForField($fieldId, $versionNo)
        );
    }
}
