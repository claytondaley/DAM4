<?php
/**
 * DhCommon
 *
 * @copyright  Copyright 2009-2014 David Havl
 * @license    MIT , http://davidhavl.com/license/MIT
 * @link       http://davidhavl.com
 * @author     David Havl <contact@davidhavl.com>
 */
namespace DAM4\Hydrator\Strategy;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class DateTimeStrategy implements StrategyInterface
{
    protected $allowNull = true;
    protected $format = 'Y-m-d H:i:s';

    /**
     * @param bool $allowNull sets if null value is allowed.
     */
    public function __construct($allowNull = true)
    {
        $this->allowNull = (bool) $allowNull;
    }

    /**
     * Set if null value is allowed. Extract method will convert it to empty string and Hydrate method will convert empty value to null.
     *
     * @param boolean $allowNull
     */
    public function setAllowNull($allowNull)
    {
        $this->allowNull = $allowNull;
    }

    /**
     * Get if null value is allowed.
     *
     * @return boolean
     */
    public function getAllowNull()
    {
        return $this->allowNull;
    }

    /**
     * Set format of the string extracted. Should be in PHP date format.
     *
     * @param string $extractFormat format of the string extracted in PHP date format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Get format of the string extracted.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }


    /**
     * {@inheritdoc}
     *
     * Convert a DateTime object value into a string
     */
    public function extract($value)
    {
        // check for null
        if ($value === null) {
            if ($this->allowNull) {
                return $value;
            } else {
                return "";
            }
        }

        // make sure we have DateTime
        if (!$value instanceof DateTime) {
            throw new \InvalidArgumentException('Wrong argument, expected DateTime');
        }

        // convert to string
        return $value->format($this->format);
    }


    /**
     * {@inheritdoc}
     *
     * Convert a string value into a DateTime object
     */
    public function hydrate($value)
    {
        if (empty($value) && $this->allowNull) {
            $value = null;
        } elseif (is_string($value)) {
            $value = new DateTime($value);
        }

        return $value;
    }
}