<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\MultiPrice\Values;

/**
 * Currency value object. This object will be used to represent a currency.
 */
class Currency
{
    /**
     * Unique identifier for this currency.
     *
     * @var int
     */
    public $id;

    /**
     * Currency code. Example EUR for Euros.
     *
     * @var string
     */
    public $code;

    /**
     * The Symbol for this currency. Example £ for GBP.
     *
     * @var string
     */
    public $symbol;

    /**
     * Locale for this currency. Example eng-GB.
     *
     * @var string
     */
    public $locale;
}
