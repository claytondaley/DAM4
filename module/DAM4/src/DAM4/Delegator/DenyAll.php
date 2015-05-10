<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 5/10/2015
 * Time: 10:30 AM
 */

namespace DAM4\Delegator;


use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DenyAll implements DelegatorFactoryInterface
{

    /**
     * A factory that creates delegates of a given service
     *
     * @param ServiceLocatorInterface $serviceLocator the service locator which requested the service
     * @param string $name the normalized service name
     * @param string $requestedName the requested service name
     * @param callable $callback the callback that is responsible for creating the service
     *
     * @return mixed
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        $em = $callback();
        $em->getFilters()->enable('denyall');
        return $em;
    }
}