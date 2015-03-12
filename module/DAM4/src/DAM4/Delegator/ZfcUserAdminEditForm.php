<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/10/2015
 * Time: 10:53 PM
 */

namespace DAM4\Delegator;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ZfcUserAdminEditForm implements DelegatorFactoryInterface
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
        /** @var $form \ZfcUserAdmin\Form\EditUser */
        $form = $callback();
        /*
         * Includes by default:
         *  - Username
         *  - Email
         *  - Full Name
         *  - Password
         *  - Reset Password
         */


        /** @var ClassMethods $hydrator */
        $hydrator = $form->getHydrator();
        /** @var EntityManager $object_manager */
        $object_manager = $form->getServiceManager()->get('doctrine.entitymanager.orm_default');

        $user_class = $form->getServiceManager()->get('zfcuser_module_options')->getUserEntityClass();
        $form->add(
            array(
                'type' => 'select',
                'name' => 'state',
                'options' => array(
                    'label' => 'Status',
                    'value_options' => $user_class::$stateNames,
                ),
            ),
            array(
                // Try to keep below random password checkbox
                'priority' => -50,
            )
        );

        $form->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'usergroup',
                'options' => array(
                    'label' => 'Group',
                    'object_manager' => $object_manager,
                    'target_class' => 'LegacyRS\Entity\Usergroup',
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findAll',
                    ),
                ),
            ),
            array(
                // Try to keep below random password checkbox
                'priority' => -50,
            )
        );
        $hydrator->addStrategy('usergroup', $form->getServiceManager()->get('DAM4\Hydrator\Strategy\UsergroupStrategy'));

        $form->add(
            array(
                'type' => 'textarea',
                'name' => 'ip_restrict',
                'attributes' => array(
                    'cols' => '160',
                    'rows' => '3',
                ),
                'options' => array(
                    'label' => 'IP Address Restrictions',
                ),
            ),
            array(
                // Try to keep below random password checkbox
                'priority' => -50,
            )
        );

        $form->add(
            array(
                'type' => 'textarea',
                'name' => 'comments',
                'attributes' => array(
                    'cols' => '160',
                    'rows' => '3',
                ),
                'options' => array(
                    'label' => 'Comments',
                ),
            ),
            array(
                // Try to keep below random password checkbox
                'priority' => -50,
            )
        );

        $form->add(
            array(
                'type' => 'datetimelocal',
                'name' => 'created',
                'attributes' => array(
                    'readonly' => true,
                ),
                'options' => array(
                    'label' => 'Created',
                ),
            ),
            array(
                // Try to keep below random password checkbox
                'priority' => -50,
            )
        );

        $form->add(
            array(
                'type' => 'datetimelocal',
                'name' => 'last_active',
                'attributes' => array(
                    'readonly' => true,
                ),
                'options' => array(
                    'label' => 'Last Active',
                ),
            ),
            array(
                // Try to keep below random password checkbox
                'priority' => -50,
            )
        );

        $form->add(
            array(
                'type' => 'text',
                'name' => 'last_browser',
                'attributes' => array(
                    'readonly' => true,
                ),
                'options' => array(
                    'label' => 'Last Browser',
                ),
            ),
            array(
                // Try to keep below random password checkbox
                'priority' => -50,
            )
        );

        return $form;
    }
}