<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\ApiLoader;

use eZ\Publish\Core\Persistence\Database\DatabaseHandler;
use EzSystems\EzPriceBundle\API\Vat\Handler\DynamicVatHandler;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway\DoctrineDatabase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LegacyVatHandlerFactory
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Builds the legacy vat handler.
     *
     * @param \eZ\Publish\Core\Persistence\Database\DatabaseHandler $dbHandler
     * @param DynamicVatHandler                                     $dynamicVatHandler The dynamic vat handler to use
     *
     * @return \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\VatHandler
     */
    public function buildLegacyVatHandler(DatabaseHandler $dbHandler, DynamicVatHandler $dynamicVatHandler = null)
    {
        $legacyVatHandlerClass = $this->container->getParameter('ezprice.api.storage_engine.legacy.handler.ezprice.vathandler.class');

        return new $legacyVatHandlerClass(
            new DoctrineDatabase($dbHandler),
            $dynamicVatHandler
        );
    }
}
