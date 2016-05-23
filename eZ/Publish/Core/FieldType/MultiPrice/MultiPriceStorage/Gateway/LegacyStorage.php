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

use eZ\Publish\SPI\Persistence\Content\Field;
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
     * Used to store the values of the prices in the ezmultiprice data table.
     *
     * @param Field $field     The field to store the values for
     * @param int   $versionNo The ID of the version of the field that we are storing
     *
     * @return bool
     */
    public function storeFieldData(Field $field, $versionNo)
    {
        foreach ($field->value->externalData['prices'] as $price) {
            if ($this->doesCurrencyDataExist($price['currency_code'], $field->id, $versionNo)) {
                $this->updateCurrencyPrice($price, $field->id, $versionNo);
            } else {
                $this->addNewCurrencyPrice($price, $field->id, $versionNo);
            }
        }

        return true;
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
            ->select(array('currency_code', 'id', 'value', 'type'))
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

    /**
     * Update the row in the ezmultipricedata table for a specific currency.
     *
     * @param array $priceData The values that we will use to update the price
     *                         row with
     * @param int   $fieldId   The id of the field that we are updating
     * @param int   $versionNo The id of the version that we are updating
     *
     * @return null
     */
    protected function updateCurrencyPrice($priceData, $fieldId, $versionNo)
    {
        $updateQuery = $this->dbHandler->createUpdateQuery();
        $updateQuery->update($this->dbHandler->quoteTable('ezmultipricedata'))
            ->set(
                $this->dbHandler->quoteColumn('value'),
                $updateQuery->bindValue($priceData['value'])
            )->set(
                $this->dbHandler->quoteColumn('type'),
                $updateQuery->bindValue($priceData['type'])
            )->where(
                $updateQuery->expr->lAnd(
                    $updateQuery->expr->eq(
                        $this->dbHandler
                            ->quoteColumn('contentobject_attr_id'),
                        $updateQuery->bindValue($field, null, \PDO::PARAM_INT)
                    ),
                    $updateQuery->expr->eq(
                        $this->dbHandler
                            ->quoteColumn('contentobject_attr_version'),
                        $updateQuery->bindValue($versionNo, null, \PDO::PARAM_INT)
                    ),
                    $updateQuery->expr->eq(
                        $this->dbHandler
                            ->quoteColumn('currency_code'),
                        $updateQuery->bindValue($priceData['currency_code'], null, \PDO::PARAM_STR)
                    )
                )
            );

        $updateQuery->prepare()->execute();
    }

    /**
     * Check if there is an existing record for this currency.
     *
     * @param string $currencyCode The currency code that we are updating
     * @param int    $fieldId      The id of the field that we are looking for.
     * @param int    $versionNo    The Version id of the field that we are looking for
     *
     * @return bool true if data exists, otherwise false
     */
    protected function doesCurrencyDataExist($currencyCode, $fieldId, $versionNo)
    {
        return $this->getCurrencyData($currencyCode, $fieldId, $versionNo) !== null;
    }

    /**
     * Get the row for a currency on a field.
     *
     * @param string $currencyCode The currency code that we are updating
     * @param int    $fieldId      The id of the field that we are looking for.
     * @param int    $versionNo    The Version id of the field that we are looking for
     *
     * @return null|array Null if not found, otherwise return the currency row
     */
    protected function getCurrencyData($currencyCode, $fieldId, $versionNo)
    {
        $query = $this->dbHandler
                    ->createSelectQuery();
        $query
            ->select(array('currency_code', 'id', 'value', 'type'))
            ->from($this->dbHandler
                        ->quoteTable('ezmultipricedata')
            )
            ->where(
                $query->expr->lAnd(
                    $query->expr->eq(
                        $this->dbHandler
                            ->quoteColumn('contentobject_attr_id'),
                        $query->bindValue($fieldId, null, PDO::PARAM_INT)
                    ),
                    $query->expr->eq(
                        $this->dbHandler
                            ->quoteColumn('contentobject_attr_version'),
                        $query->bindValue($versionNo, null, PDO::PARAM_INT)
                    ),
                    $query->expr->eq(
                        $this->dbHandler
                            ->quoteColumn('currency_code'),
                        $query->bindValue($currencyCode, null, PDO::PARAM_STR)
                    )
                )
            )
            ->limit(1); // There should only be 1 row foreach currency;

        $statement = $query->prepare();
        $statement->execute();

        $rows = $statement->fetchAll();

        return (count($rows) === 0) ? null : $rows[0];
    }

    /**
     * Insert new currency for a field.
     *
     * @param array $priceData The values that we will use create the price row
     * @param int   $fieldId   The id of the field that we are inserting the
     *                         new currency for
     * @param int   $versionNo The id of the version that we are inserting
     *
     * @return null
     */
    protected function addNewCurrencyPrice($priceData, $fieldId, $versionNo)
    {
        $insertQuery = $this->dbHandler
                            ->createInsertQuery();
        $insertQuery->insertInto(
            $this->dbHandler
                ->quoteTable('ezmultipricedata')
        )
            ->set(
                $this->dbHandler
                    ->quoteColumn('currency_code'),
                $insertQuery->bindValue($priceData['currency_code'])
            )->set(
                $this->dbHandler
                    ->quoteColumn('value'),
                $insertQuery->bindValue($priceData['value'])
            )->set(
                $this->dbHandler
                    ->quoteColumn('type'),
                $insertQuery->bindValue($priceData['type'])
            )->set(
                $this->dbHandler
                    ->quoteColumn('contentobject_attr_id'),
                $insertQuery->bindValue($fieldId, null, \PDO::PARAM_INT)
            )->set(
                $this->dbHandler
                    ->quoteColumn('contentobject_attr_version'),
                $insertQuery->bindValue($versionNo, null, \PDO::PARAM_INT)
            );

        $insertQuery->prepare()->execute();
    }
}
