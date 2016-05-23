<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\Gateway;

use eZ\Publish\Core\Persistence\Database\DatabaseHandler;
use EzSystems\EzPriceBundle\API\Vat\Values\CountryVatRule;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\Vat\CountryVatRules\Gateway;
use EzSystems\EzPriceBundle\Core\Vat\CountryVatRuleNotFoundException;
use PDO;

/**
 * Extends gateway and retrieves information about country based vat rules from the Database.
 */
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
     * Queries database to get all vat rules for a given country.
     *
     * @param int $country
     *
     * @throws CountryVatRuleNotFoundException if there are no VAT rates available for this country
     *
     * @return array all possible rules that can be applied to the users in the country
     *               specified
     */
    public function getVatRulesForCountry($country)
    {
        $query = $this->handler->createSelectQuery();

        /*
         * Query gets all rows from the ezvatrule table where the country is either the wildcard
         * or the country specified.
         */
        $query
            ->select(array('vat_type', 'country_code', 'id'))
            ->from($this->handler->quoteTable('ezvatrule'))
            ->where(
                $query->expr->LOr(
                    $query->expr->eq(
                        $this->handler->quoteColumn('country_code'),
                        $query->bindValue($country, null, PDO::PARAM_STR)
                    ),
                    $query->expr->eq(
                        $this->handler->quoteColumn('country_code'),
                        $query->bindValue('*', null, PDO::PARAM_STR)
                    )
                )
            );

        $statement = $query->prepare();
        $statement->execute();
        if ($statement->rowCount() === 0) {
            throw new CountryVatRuleNotFoundException("Vat Rate could not be found for {$country}");
        }

        return $statement->fetchAll();
    }

    /**
     * Fetch a single vat rule by its ID.
     *
     * @param int $id the ID to fetch the vat rule by
     *
     * @throws CountryVatRuleNotFoundException if the vat rule with this ID cannot be found§
     *
     * @return array the data for the vatrule retrieved
     */
    public function getVatRuleById($id)
    {
        $query = $this->handler->createSelectQuery();
        $query
            ->select(array('vat_type', 'country_code', 'id'))
            ->from($this->handler->quoteTable('ezvatrule'))
            ->where(
                $query->expr->eq(
                    $this->handler->quoteColumn('id'),
                    $query->bindValue($id, null, PDO::PARAM_INT)
                )
            );

        $statement = $query->prepare();
        $statement->execute();

        if ($statement->rowCount() === 0) {
            throw new CountryVatRuleNotFoundException("Vat Rate not found with ID {$id}");
        }

        return $statement->fetch();
    }

    /**
     * Used to translate the data retrieved by this gateway into CountryVatRule objects.
     *
     * @param array $data the data to populate the CountryVatRule object with
     *
     * @return CountryVatRule with properties from $data
     */
    public function translateDataToCountryVatRule($data)
    {
        $countryVatRule = new CountryVatRule();
        $countryVatRule->id = $data['id'];
        $countryVatRule->countryCode = $data['country_code'];
        $countryVatRule->vatRateId = $data['vat_type'];

        return $countryVatRule;
    }
}
