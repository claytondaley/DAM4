<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 4/4/2015
 * Time: 8:07 PM
 */

namespace DAM4\Factory\Listener;

use DAM4\Listener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class RbacNavigation implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authorizationService = $serviceLocator->get('ZfcRbac\Service\AuthorizationService');

        return new Listener\RbacNavigation($authorizationService);
    }
}