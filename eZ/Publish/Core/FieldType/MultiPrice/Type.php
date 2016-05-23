<?php

/**
 * This file is part of the EzPriceBundle package.
 *
 * @author Bluetel Solutions <developers@bluetel.co.uk>
 * @author Joe Jones <jdj@bluetel.co.uk>
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\Value as BaseValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use EzSystems\EzPriceBundle\API\MultiPrice\Values\Price as PriceValueObject;

class Type extends FieldType
{
    /**
     * Returns the field type identifier for this field type.
     *
     * @return string
     */
    public function getFieldTypeIdentifier()
    {
        return 'ezmultiprice';
    }

    /**
     * Returns the name of the given field value.
     *
     * It will be used to generate content name and url alias if current field is designated
     * to be used in the content name/urlAlias pattern.
     *
     * @param \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value $value
     *
     * @return string
     */
    public function getName(SPIValue $value)
    {
        if (count($value->prices) == 0) {
            return '';
        }
        $firstPrice = array_slice($value->prices, 0, 1);

        return $firstPrice[0]->value;
    }

    /**
     * Returns the fallback default value of field type when no such default
     * value is provided in the field definition in content types.
     *
     * @return \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value
     */
    public function getEmptyValue()
    {
        return new Value();
    }

    /**
     * Implements the core of {@see isEmptyValue()}.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isEmptyValue(SPIValue $value)
    {
        return count($value->prices) === 0;
    }

    /**
     * Inspects given $inputValue and potentially converts it into a dedicated value object.
     *
     * @param \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value $inputValue
     *
     * @throws InvalidArgumentException if the argument supplied is not a MultiPrice Value object
     *
     * @return \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value The potentially converted and structurally plausible value.
     */
    protected function createValueFromInput($inputValue)
    {
        if ($inputValue instanceof Value) {
            return $inputValue;
        } elseif (is_array($inputValue) && array_key_exists('prices', $inputValue)) {
            $inputValue['vatTypeId'] = array_key_exists('vatTypeId', $inputValue) ? $inputValue['vatTypeId'] : null;
            $inputValue['isVatIncluded'] = array_key_exists('isVatIncluded', $inputValue) ? $inputValue['isVatIncluded'] : true;

            return new Value($inputValue['prices'], $inputValue['vatTypeId'], $inputValue['isVatIncluded']);
        }

        return $inputValue;
    }

    /**
     * Throws an exception if value structure is not of expected format.
     *
     *
     * @param \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value $value
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If the value does not match the expected structure.
     *
     * @return void
     */
    protected function checkValueStructure(BaseValue $value)
    {
        if (!is_array($value->prices)) {
            throw new InvalidArgumentType(
                    '$value->prices',
                    'array',
                    $value->prices
                );
        }

        // Validate prices are all floats.
        foreach ($value->prices as $currency => $price) {
            if (!$price instanceof PriceValueObject) {
                throw new InvalidArgumentType(
                    '$value->prices[\''.$currency.'\']',
                    '\EzSystems\EzPriceBundle\API\MultiPrice\Values\Price',
                    $price
                );
            } elseif (!is_float($price->value) && !is_int($price->value)) {
                throw new InvalidArgumentType(
                    '$value->prices[\''.$currency.'\']->value',
                    'float|int',
                    $price->value
                );
            }
        }
        // Validate vatTypeId, should not be null
        if ($value->vatTypeId === null) {
            throw new InvalidArgumentType(
                '$value->vatTypeId',
                'not null',
                $value->vatTypeId
            );
        }
        // Validate isVatIncluded, should not be null
        if ($value->isVatIncluded === null) {
            throw new InvalidArgumentType(
                '$value->isVatIncluded',
                'not null',
                $value->isVatIncluded
            );
        }
    }

    /**
     * Returns information for FieldValue->$sortKey relevant to the field type.
     *
     * @param \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value $value
     *
     * @return array
     */
    protected function getSortInfo(BaseValue $value)
    {
        $firstPrice = array_slice($value->prices, 0, 1);

        return (int) ($firstPrice->value * 100.00);
    }

    /**
     * Converts an $hash to the Value defined by the field type.
     *
     * @param mixed $hash
     *
     * @return \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value $value
     */
    public function fromHash($hash)
    {
        if ($hash === null || !array_key_exists('prices', $hash)) {
            return $this->getEmptyValue();
        }
        foreach ($hash['prices'] as $currency => $price) {
            $hash['prices'][$currency] = new PriceValueObject($price);
        }
        $hash['vatTypeId'] = array_key_exists('vatTypeId', $hash) ? $hash['vatTypeId'] : null;
        $hash['isVatIncluded'] = array_key_exists('isVatIncluded', $hash) ? $hash['isVatIncluded'] : true;

        return new Value($hash['prices'], $hash['vatTypeId'], $hash['isVatIncluded']);
    }

    /**
     * Converts a $Value to a hash.
     *
     * @param \EzSystems\EzPriceBundle\eZ\Publish\Core\FieldType\MultiPrice\Value $value
     *
     * @return mixed
     */
    public function toHash(SPIValue $value)
    {
        if ($this->isEmptyValue($value)) {
            return;
        }
        foreach ($value->prices as $currency => $price) {
            $value->prices[$currency] = (array) $price;
        }

        return (array) $value;
    }

    /**
     * Returns whether the field type is searchable.
     *
     * @return bool
     */
    public function isSearchable()
    {
        return true;
    }

    public function toPersistenceValue(SPIValue $value)
    {
        return new FieldValue(
            array(
                'data'         => null,
                'externalData' => $this->toHash($value),
                'sortKey'      => null,
            )
        );
    }

    /**
     * Converts a persistence $fieldValue to a Value.
     *
     * This method builds a field type value from the $data and $externalData properties.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\FieldValue $fieldValue
     *
     * @return \eZ\Publish\Core\FieldType\MultiPrice\Value
     */
    public function fromPersistenceValue(FieldValue $fieldValue)
    {
        return new Value(
            $fieldValue->externalData,
            $fieldValue->data['vatRateId'],
            $fieldValue->data['isVatIncluded']
        );
    }
}
