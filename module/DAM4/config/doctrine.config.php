<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/12/2015
 * Time: 8:31 PM
 */

return array(
    'driver' => array(
        'legacy_rs_entities' => array(
            'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            'cache' => 'array',
            'paths' => array(getcwd() . '/vendor/claytondaley/legacyrs/src/LegacyRS/Entity')
        ),
        'orm_default' => array(
            'drivers' => array(
                'LegacyRS\Entity' => 'legacy_rs_entities'
            )
        )
    ),
    'configuration' => array(
        'orm_default' => array(
            'types' => array(
                'timestamp' => 'DAM4/Type/TimestampType',
            ),
            'filters'  =>  array(
                'denyall' => 'DAM4\Doctrine\Filter\DenyAll'
            ),
        )
    ),
);