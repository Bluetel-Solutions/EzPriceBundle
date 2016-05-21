<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat\Gateway;

use eZ\Publish\Core\Persistence\Database\DatabaseHandler;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat\Gateway;
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
     * Gets the id of the vat rate associated with $fieldId and $versionNo.
     *
     * @param $fieldId
     * @param $versionNo
     *
     * @throws \EzSystems\EzPriceBundle\Core\Persistence\Legacy\Price\ContentVat\Gateway\VatIdentifierNotFoundException
     *                                                                                                                  when vat_identifier can't be found (empty data_text).
     *
     * @return int;
     */
    public function getVatRateId($fieldId, $versionNo)
    {
        $query = $this->handler->createSelectQuery();
        $query
            ->select('data_text')
            ->from($this->handler->quoteTable('ezcontentobject_attribute'))
            ->where(
                $query->expr->lAnd(
                    $query->expr->eq(
                        $this->handler->quoteColumn('id'),
                        $query->bindValue($fieldId, null, PDO::PARAM_INT)
                    ),
                    $query->expr->eq(
                        $this->handler->quoteColumn('version'),
                        $query->bindValue($versionNo, null, PDO::PARAM_INT)
                    )
                )
            );

        $statement = $query->prepare();
        $statement->execute();

        $rowDataText = $statement->fetchColumn();
        if (empty($rowDataText)) {
            throw new VatIdentifierNotFoundException('Vat Identifier for ', $fieldId.' - '.$versionNo);
        }

        list($vatRateId) = explode(',', $rowDataText);

        return $vatRateId;
    }
}
