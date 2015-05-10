<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 */

namespace DAM4\Hydrator\Strategy;

use DateTime;
use Doctrine\ORM\EntityManager;
use LegacyRS\Entity\Usergroup;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class UsergroupStrategy implements StrategyInterface, ServiceLocatorAwareInterface
{
    protected $allowNull = false;

    /**
     * @var ServiceManager
     */
    protected $serviceLocator;

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

        // convert to int
        return $value->getId();
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
        } elseif (is_numeric($value)) {
            /** @var EntityManager $em */
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $value = $em->getRepository('LegacyRS\Entity\Usergroup')->find($value);
        }

        return $value;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceLocatorInterface $serviceManager
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceLocator = $serviceManager;
        return $this;
    }
}