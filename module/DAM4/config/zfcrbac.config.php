<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/12/2015
 * Time: 8:31 PM
 */

use ZfcRbac\Guard\GuardInterface;

return array (
    'protection_policy' => GuardInterface::POLICY_DENY,
    'guest_role' => '-1',
    'role_provider_manager' => array(
        'factories' => array(
            'DAM4\Role\RoleProvider' => 'DAM4\Factory\Role\RoleProvider'
        ),
        'delegators' => array(
            'DAM4\Role\RoleProvider' => array(
                'DAM4\Delegator\Doctrine\PermitUsergroup',
            ),
        ),
    ),
    'role_provider' => array(
        'DAM4\Role\RoleProvider' => array(
        ),
    ),
    'guards' => array (
        'ZfcRbac\Guard\ControllerPermissionsGuard' => array (
            // must have admin ('a') to access admin
            array (
                'controller' => 'ZfcAdmin\Controller\AdminController',
                'permissions' => ['a'],
            ),
            // must have admin ('a') and manage users ('u') to reach user admin
            array (
                'controller' => 'zfcuseradmin',
                'permissions' => ['a', 'u'],
            ),
            // zfcuser automatically requires an authenticated user for problem routes
            // only login and register are available by default
            // eventually, we will manage register using a setting, but not yet
            array (
                'controller' => 'zfcuser',
                'permissions' => ['*'],
            ),
            // enable forgot password page for guests
            array (
                'controller' => 'goalioforgotpassword_forgot',
                'permissions' => ['*'],
            ),
            // this should be redirected not unauthorized
            array (
                'controller' => 'LegacyRS\Controller\LegacyRS',
                'permissions' => ['user'],
            ),
            array (
                'controller' => 'DAM4\Controller\Index',
                'permissions' => ['user'],
            ),
            array (
                'controller' => 'LegacyRS\Controller\Redirect',
                'permissions' => ['*'],
            ),
        ),
    ),

    'redirect_strategy' => array(
        'redirect_when_connected'        => false,
        'redirect_to_route_connected'    => 'legacyrs',
        'redirect_to_route_disconnected' => 'zfcuser/login',
        'append_previous_uri'            => true,
        'previous_uri_query_key'         => 'redirect'
    ),

);