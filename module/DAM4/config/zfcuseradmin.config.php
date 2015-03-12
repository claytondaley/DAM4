<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/12/2015
 * Time: 8:32 PM
 */

return array(
    /**
     * Mapper for ZfcUser
     *
     * Set the mapper to be used here
     * Currently Available mappers
     *
     * ZfcUserAdmin\Mapper\UserDoctrine
     *
     * By default this is using
     * ZfcUserAdmin\Mapper\UserZendDb
     */
    'user_mapper' => 'ZfcUserAdmin\Mapper\UserDoctrine',

    /**
     * Array of data to show in the user list
     * Key = Label in the list
     * Value = entity property(expecting a 'getProperty())
     */
    'user_list_elements' => array(
        'Status' => 'state',
        'Username' => 'username',
        'Full Name' => 'display_name',
        'Email' => 'email',
    ),
);