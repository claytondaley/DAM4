<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 */

namespace DAM4\Hydrator\Strategy;

use DateTime;
use LegacyRS\Entity\Usergroup;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class UsergroupNameStrategy implements StrategyInterface
{
    protected $allowNull = false;

    /**
     * @param bool $allowNull sets if null value is allowed.
     */
    public function __construct($allowNull = false)
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
     * {@inheritdoc}
     *
     * Convert a Usergroup object value into a string
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
        if (!$value instanceof Usergroup) {
            throw new \InvalidArgumentException('Wrong argument, expected Usergroup');
        }

        // convert to string
        return $value->getName();
    }


    /**
     * {@inheritdoc}
     *
     * Convert a string value into a Usergroup object
     */
    public function hydrate($value)
    {
        if (empty($value) && $this->allowNull) {
            $value = null;
        } elseif (is_int($value)) {
            $value = array('name' => $value);
        }

        return $value;
    }
}