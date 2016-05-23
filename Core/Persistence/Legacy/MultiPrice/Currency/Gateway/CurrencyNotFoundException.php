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

use eZ\Publish\API\Repository\Exceptions\NotImplementedException;

/**
 * Exception used when the currency is not found.
 */
class CurrencyNotFoundException extends NotImplementedException
{
}
