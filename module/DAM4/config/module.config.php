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
        'factories' => array(
            'header_left' => 'DAM4\Navigation\LeftMenuFactory',
            'header_right' => 'DAM4\Navigation\RightMenuFactory',
            'DAM4\Role\RoleProvider' => 'DAM4\Role\Factory\RoleProviderFactory',
        ),
        'invokables' => array(
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
            'DAM4\Controller\Redirect'  => 'DAM4\Controller\RedirectController',
        ),
    ),

    'navigation' => array(
        'header_left' => array(
            array(
                'label' => 'Home',
                'route' => 'legacyrs',
            ),
        ),
        'header_right' => array(
            array(
                'label' => 'User',
                'route' => 'zfcuser',
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'legacyrs' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'LegacyRS\Controller',
                        'controller'    => 'LegacyRS',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array (
                    'home' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => 'application',
                            'defaults' => array(
                                '__NAMESPACE__' => 'LegacyRS\Controller',
                                'controller'    => 'LegacyRS',
                                'action'        => 'index',
                            ),
                        ),
                    ),
                    # Hijack login (and logout by query string) route and redirect to authentication providers
                    'hijack-login' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => 'login.php',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DAM4\Controller',
                                'controller'    => 'Index',
                                'action'        => 'login',
                            ),
                        ),
                    ),
                    # Hijack user profile call and redirect to ZfcUser page
                    'hijack-profile' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => 'pages/user_preferences.php',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DAM4\Controller',
                                'controller'    => 'Redirect',
                                'action'        => 'redirect',
                                'route'         => 'zfcuser'
                            ),
                        ),
                    ),
                    # Hijack user admin page and redirect to ZfcUserAdmin page
                    'hijack-user-list' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => 'pages/team/team_user.php',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DAM4\Controller',
                                'controller'    => 'Redirect',
                                'action'        => 'redirect',
                                'route'         => 'zfcadmin/zfcuseradmin/list'
                            ),
                        ),
                    ),
                    # Hijack user admin page and redirect to ZfcUserAdmin page
                    'hijack-user-edit' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => 'pages/team/team_user_edit.php',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DAM4\Controller',
                                'controller'    => 'Redirect',
                                'action'        => 'redirect',
                                'route'         => 'zfcadmin/zfcuseradmin/edit'
                            ),
                        ),
                    ),
                ),
            ),
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
