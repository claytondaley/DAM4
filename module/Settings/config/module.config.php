<?php
/**
 * Copyright (C) 2014 Clayton Daley III
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

return array(
    'doctrine' => array(
        'driver' => array(
            'legacy_rs_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../LegacyRS/src/LegacyRS/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'LegacyRS\Entity' => 'legacy_rs_entities'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Settings\Controller\Settings' => 'Settings\Controller\SettingsController',
            'Settings\Controller\Setup' => 'Settings\Controller\SetupController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'settings' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/settings',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Settings\Controller',
                        'controller'    => 'Settings',
                        'action'        => 'index'
                    )
                )
            ),
            'setup' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/setup',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Settings\Controller',
                        'controller'    => 'Setup',
                        'action'        => 'index'
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
#        'template_map' => array(
#            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
#            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
#            'error/404'               => __DIR__ . '/../view/error/404.phtml',
#            'error/index'             => __DIR__ . '/../view/error/index.phtml',
#        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);