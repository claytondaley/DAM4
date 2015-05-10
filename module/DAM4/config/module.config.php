<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'doctrine' => include 'doctrine.config.php',
    'zfc_rbac' => include 'zfcrbac.config.php',
    'zfcuseradmin' => include 'zfcuseradmin.config.php',
    'LegacyRS' => include 'legacyrs.config.php',

    'form_elements' => array(
        'delegators' => array(
            'zfcuseradmin_createuser_form' => array(
                'DAM4\Delegator\ZfcUserAdminCreateForm',
            ),
            'zfcuseradmin_edituser_form' => array(
                'DAM4\Delegator\ZfcUserAdminEditForm',
            ),
            'ZfcUserAdmin\Table\UserList' => array(
                'DAM4\Delegator\ZfcUserAdminUserList',
            ),
        )
    ),

    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'Zend\Authentication\AuthenticationService' => 'zfcuser_auth_service'
        ),
        'delegators' => array(
            'doctrine.entitymanager.orm_default' => array(
                'DAM4\Delegator\DenyAll',
            ),
        ),
        'factories' => array(
            'header_left' => 'DAM4\Navigation\LeftMenuFactory',
            'header_right' => 'DAM4\Navigation\RightMenuFactory',
            'DAM4\Role\RoleProvider' => 'DAM4\Role\Factory\RoleProviderFactory',
            'DAM4\Listener\RbacNavigation' => 'DAM4\Factory\Listener\RbacNavigation',
        ),
        'invokables' => array(
            'DAM4\Doctrine\Fitler\DenyAll'                  => 'DAM4\Doctrine\Fitler\DenyAll',
            'DAM4\Listener\ZfcUserListener'                 => 'DAM4\Listener\ZfcUserListener',
            'DAM4\Listener\MvcEventListener'                => 'DAM4\Listener\MvcEventListener',
            'DAM4\Hydrator\Strategy\DateTimeStrategy'       => 'DAM4\Hydrator\Strategy\DateTimeStrategy',
            'DAM4\Hydrator\Strategy\UsergroupNameStrategy'  => 'DAM4\Hydrator\Strategy\UsergroupNameStrategy',
            'DAM4\Hydrator\Strategy\UsergroupStrategy'      => 'DAM4\Hydrator\Strategy\UsergroupStrategy',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'DAM4\Controller\Index'     => 'DAM4\Controller\IndexController',
        ),
    ),

    'navigation' => array(
        'header_left' => array(
            array (
                'label' => 'Recently Added',
                'type' => 'uri',
                'uri' => '/pages/search.php?search=%21last1000&order_by=resourceid',
                'permission' => 'user',
            ),
            array (
                'label' => 'Research Request',
                'type' => 'uri',
                'uri' => '/pages/research_request.php',
                'permission' => 'q',
            ),
            array (
                'label' => 'Help and Advice',
                'type' => 'uri',
                'uri' => '/pages/help.php',
                'permission' => 'user',
            ),
            array (
                'label' => 'Upload',
                'type' => 'uri',
                'uri' => '/pages/edit.php?ref=-1&uploader=plupload',
                'permission' => 'd',
            ),
            array (
                'label' => 'Team Center',
                'type' => 'uri',
                'uri' => '/pages/team/team_home.php',
                'permission' => 't',
            ),
        ),
        'header_right' => array(
            array(
                'label' => 'User',
                'route' => 'zfcuser',
                'permission' => 'user',
            ),
            array(
                'label' => 'Logout',
                'route' => 'zfcuser/logout',
                'permission' => 'user',
            ),
            array(
                'label' => 'Contact Us',
                'type' => 'uri',
                'uri' => '/pages/contact.php',
                'permission' => 'user',
            )
        ),
    ),

    // Regular Routes
    'router' => array(
        'routes' => array(
        ),
    ),

    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/admin'            => __DIR__ . '/../view/layout/layout.phtml',
            'layout/ajax'             => __DIR__ . '/../view/layout/ajax.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
);
