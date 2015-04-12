<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 4/9/2015
 * Time: 5:41 PM
 */
return array (
    /*
     * The following routes are currently supported
     *  - login
     *  - logout
     *  - userProfile (current user, no ID provided)
     *  - adminUserList
     *  - adminUserEdit ('ref' carries user ID)
     */
    'routes' => array (
        'login' => array (
            'route' => 'zfcuser/login',
            'ajax'  => false, # Optional, indicates ajax deliver is not permitted
        ),
        'logout' => array (
            'route' => 'zfcuser/logout',
            'ajax'  => false, # Optional, indicates ajax deliver is not permitted
        ),
        'userProfile' => array (
            'route' => 'zfcuser',
            'ajax'  => false, # Optional, indicates ajax deliver is not permitted
        ),
        'adminUserList' => array (
            'route' => 'zfcadmin/zfcuseradmin/list',
            'ajax'  => false, # Optional, indicates ajax deliver is not permitted
        ),
        'adminUserEdit' => array (
            'route' => 'zfcadmin/zfcuseradmin/edit',
            'ajax'  => false, # Optional, indicates ajax deliver is not permitted
            # Converts a query into a parameter for the route
            'parameterize' => array(
                'ref' => 'userId',
            ),
        ),
    ),
);
