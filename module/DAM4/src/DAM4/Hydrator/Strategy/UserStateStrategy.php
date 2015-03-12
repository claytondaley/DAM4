<?php
namespace DAM4\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class UserStateStrategy implements StrategyInterface
{
    protected $stateNames = array('No', 'Yes');

    /**
     * @param array $stateNames array of names for integer states.
     */
    public function __construct(array $stateNames = null)
    {
        if(is_null($stateNames)) {}
        elseif (is_array($stateNames)) {
            $this->stateNames = $stateNames;
        } else {
            throw new \InvalidArgumentException('Wrong argument, expected array or null');
        }
    }

    /**
     * Set if null value is allowed. Extract method will convert it to empty string and Hydrate method will convert empty value to null.
     *
     * @param array $stateNames
     * @return $this
     */
    public function setStateNames($stateNames)
    {
        $this->stateNames = $stateNames;
        return $this;
    }

    /**
     * Get if null value is allowed.
     *
     * @return array
     */
    public function getStateNames()
    {
        return $this->stateNames;
    }

    /**
     * {@inheritdoc}
     *
     * Convert a state into a string
     */
    public function extract($value)
    {
        // make sure we have DateTime
        if (!is_int($value)) {
            throw new \InvalidArgumentException('Wrong argument, expected integer');
        }

        // convert to string
        return $this->getStateNames()[$value];
    }

    /**
     * {@inheritdoc}
     *
     * Convert a string into a state
     */
    public function hydrate($value)
    {
        if (empty($value)) {
            $value = null;
        } elseif (is_int($value)) {
            // assume this is the correct index and do nothing
        } elseif (is_string($value)) {
            $value = array_search($value, $this->getStateNames());
            if ($value === false) {
                throw new \InvalidArgumentException('String not present among valid states');
            }
        } else {
            throw new \InvalidArgumentException('Wrong argument, expected null, integer, or string.');
        }

        return $value;
    }
}