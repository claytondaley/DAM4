<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 1/18/2015
 * Time: 4:23 PM
 */

namespace DAM4\Factory\Role;

use DAM4\Role;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcRbac\Role\RoleProviderPluginManager;

class RoleProvider implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $roleProvider = new Role\RoleProvider;
        if ($serviceLocator instanceof RoleProviderPluginManager){
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $roleProvider->setEntityManager(
            $serviceLocator->get('Doctrine\ORM\EntityManager')
        );

        return $roleProvider;
    }
}