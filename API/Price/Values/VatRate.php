<?php
/**
 * This file is part of the EzPriceBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\API\Price\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * @property-read float $percentage
 * @property-read string $name
 */
class VatRate extends ValueObject
{
    /**
     * @var float
     */
    protected $percentage;

    /**
     * @var string
     */
    protected $name;
}
