<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\Core\Persistence\Legacy\MultiPrice\Currency;

use EzSystems\EzPriceBundle\API\MultiPrice\Values\Currency;

/**
 * Abstract gateway that will be extended and used to fetch currencies.
 */
abstract class Gateway
{
    /**
     * Used to fetch a currency by its currency code.
     *
     * @param string $code 3 character code to fetch a currency by
     *
     * @return array with the currencies data in it.
     */
    abstract public function getCurrencyByCode($code);

    /**
     * Used to translate a row revieved from the gateway into a currency object.
     *
     * @param array $data the data structure returned by the gateway.
     *
     * @return Currency with the values populated from $data.
     */
    abstract public function translateDataToCurrency($data);
}
