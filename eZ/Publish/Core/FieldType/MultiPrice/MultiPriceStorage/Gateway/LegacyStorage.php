<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\MultiPriceStorage\Gateway;

use EzSystems\EzPriceBundle\API\MultiPrice\Values\Price;
use EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\MultiPriceStorage\Gateway;
use PDO;

/**
 * Used to get additional information for the MultiPrice fieldtype.
 */
class LegacyStorage extends Gateway
{
    /**
     * Database handler.
     *
     * @var \eZ\Publish\Core\Persistence\Database\DatabaseHandler
     */
    protected $dbHandler;

    /**
     * Used to set the dbHandler property.
     *
     * @param \eZ\Publish\Core\Persistence\Database\DatabaseHandler $dbHandler
     */
    public function setConnection($dbHandler)
    {
        $this->dbHandler = $dbHandler;

        return $this;
    }

    /**
     * Fetch all multiprice data for a field.
     *
     * @param int $contentAttributeId The attribute ID to fetch the prices of.
     * @param int $versionNo          The version number to fetch the values for.
     *
     * @return array
     */
    public function getFieldData($fieldId, $versionNo)
    {
        $prices = array();
        foreach ($this->getMultiPriceDataForField($fieldId, $versionNo) as $priceData) {
            $prices[$priceData['currency_code']] = new Price($priceData);
        }

        return $prices;
    }

    /**
     * Get all information from the ezmultipricedata table that is related to the field and version
     * that we are getting the data for.
     *
     * @param int $fieldId   The Id of the field that we want the data for.
     * @param int $versionNo The version number of the field that we want the data for.
     *
     * @return array The rows from ezmultiprice data, with keys currency_code, id and value
     */
    protected function getMultiPriceDataForField($fieldId, $versionNo)
    {
        $query = $this->dbHandler->createSelectQuery();
        $query
            ->select(array('currency_code', 'id', 'value'))
            ->from($this->dbHandler->quoteTable('ezmultipricedata'))
            ->where(
                $query->expr->lAnd(
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn('contentobject_attr_id'),
                        $query->bindValue($fieldId, null, PDO::PARAM_INT)
                    ),
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn('contentobject_attr_version'),
                        $query->bindValue($versionNo, null, PDO::PARAM_INT)
                    )
                )
            );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll();
    }
}
