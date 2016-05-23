<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency\Gateway;

use eZ\Publish\Core\Persistence\Database\DatabaseHandler;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\Currency;
use EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency\Gateway;
use PDO;

/**
 * Used to fetch currency objects using doctrine.
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
     * Fetch a currency by its 3 character code.
     *
     * @param string $code the currency code to fetch the currency by
     *
     * @throws CurrencyNotFoundException If the currency is not found.
     *
     * @return array the data for the single currency retrieved by that code.
     */
    public function getCurrencyByCode($code)
    {
        $query = $this->handler->createSelectQuery();
        $query
            ->select(array('code', 'locale', 'symbol', 'id'))
            ->from($this->handler->quoteTable('ezcurrencydata'))
            ->where(
                $query->expr->eq(
                    $this->handler->quoteColumn('code'),
                    $query->bindValue($code, null, PDO::PARAM_STR)
                )
            );

        $statement = $query->prepare();
        $statement->execute();

        if ($statement->rowCount() === 0) {
            throw new CurrencyNotFoundException('Currency with code {$code} not found');
        }

        return $statement->fetch();
    }

    /**
     * Translate the data retrieved from this gateway into a currency object.
     *
     * @param array $data with keys id, code, symbol and locale.
     *
     * @return Currency The currency object populated with the values in $data;
     */
    public function translateDataToCurrency($data)
    {
        $currency = new Currency();
        $currency->id = $data['id'];
        $currency->code = $data['code'];
        $currency->symbol = $data['symbol'];
        $currency->locale = $data['locale'];

        return $currency;
    }
}
