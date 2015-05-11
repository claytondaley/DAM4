<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 5/10/2015
 * Time: 7:58 PM
 */

namespace DAM4\Delegator\Doctrine;


use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class PermitUsergroup implements DelegatorFactoryInterface
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
        if (method_exists($serviceLocator, 'getServiceLocator')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $em->getFilter('denyall')->addExclusion('LegacyRS\Entity\Usergroup');
        $object = $callback();

        return $object;
    }
}