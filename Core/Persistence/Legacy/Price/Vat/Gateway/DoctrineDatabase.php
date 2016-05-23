<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway;

use eZ\Publish\Core\Persistence\Database\DatabaseHandler;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway;
use PDO;

class DoctrineDatabase extends Gateway
{
    /**
     * Database handler.
     *
     * @var \eZ\Publish\Core\Persistence\Database\DatabaseHandler
     */
    protected $handler;

    /**
     * Construct from database handler.
     *
     * @param \eZ\Publish\Core\Persistence\Database\DatabaseHandler $handler
     */
    public function __construct(DatabaseHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Queries database to get data of the Vat Rate.
     *
     * @param int $vatRateId
     *
     * @throws \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\Vat\Gateway\VatNotFoundException
     *                                                                                                 if there is no data for $vatId
     *
     * @return array
     */
    public function getVatRateData($vatRateId)
    {
        $query = $this->handler->createSelectQuery();
        $query
            ->select(array('name', 'percentage'))
            ->from($this->handler->quoteTable('ezvattype'))
            ->where(
                $query->expr->eq(
                    $this->handler->quoteColumn('id'),
                    $query->bindValue($vatRateId, null, PDO::PARAM_INT)
                )
            );

        $statement = $query->prepare();
        $statement->execute();

        if ($statement->rowCount() === 0) {
            throw new VatNotFoundException('Vat Rate', $vatRateId);
        }

        return $statement->fetch();
    }
}
