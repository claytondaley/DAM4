<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/10/2015
 * Time: 10:53 PM
 */

namespace DAM4\Delegator;


use Doctrine\ORM\EntityManager;
use LegacyRS\Entity\User;
use DAM4\Hydrator\Strategy\DateTimeStrategy;
use DAM4\Hydrator\Strategy\UsergroupNameStrategy;
use DAM4\Hydrator\Strategy\UserStateStrategy;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ZfcUserAdminUserList implements DelegatorFactoryInterface
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
        /** @var $table \ZfcUserAdmin\Form\EditUser */
        $table = $callback();

        /** @var ClassMethods $hydrator */
        $hydrator = $table->getHydrator();

        $hydrator->addStrategy('state', new UserStateStrategy(User::$stateNames));

        $table->add(
            array(
                'type' => 'text',
                'name' => 'usergroup',
                'options' => array(
                    'label' => 'Group',
                ),
            )
        );
        $hydrator->addStrategy('usergroup', new UsergroupNameStrategy);

        $table->add(
            array(
                'type' => 'text',
                'name' => 'created',
                'options' => array(
                    'label' => 'Created',
                ),
            )
        );
        $hydrator->addStrategy('created', new DateTimeStrategy);

        $table->add(
            array(
                'type' => 'text',
                'name' => 'last_active',
                'options' => array(
                    'label' => 'Last Active',
                ),
            )
        );
        $hydrator->addStrategy('last_active', new DateTimeStrategy);

        $table->get('controls')->add(
            array(
                'type' => 'link',
                'name' => 'logs',
                'options' => array(
                    'label' => 'Log',
                    'idLinkUrl' => '/pages/team/team_user_log.php',
                    'idLinkParam' => 'ref',
                ),
            ),
            array(
                // before edit and delete
                'priority' => 10,
            )
        );

        return $table;
    }
}