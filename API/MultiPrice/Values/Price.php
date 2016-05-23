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
 * Object used to represent a price of the product for a particular currency.
 */
class Price
{
    /**
     * The currency that this price is for. 3 character code for the
     * currency.
     *
     * @var string
     */
    public $currency_code;

    /**
     * The price.
     *
     * @var float
     */
    public $value;

    /**
     * The id of this price.
     *
     * @var int
     */
    public $id;

    /**
     * Whether it is a custom price or not.
     *
     * @var int
     */
    public $type;

    /**
     * __construct.
     *
     * @param array $row with keys currency_code, value and id.
     */
    public function __construct($row)
    {
        $this->currency_code = $row['currency_code'];
        $this->value = (float) $row['value'];
        $this->id = $row['id'];
        $this->type = $row['type'];
    }
}
