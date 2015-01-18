<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 2/1/2015
 * Time: 11:15 PM
 */

return array(
    'controllers' => array(
        'initializers' => array (
            // Inject the Piwik server-side analytics package to track LegacyRS
            function ($instance, $sm) {
                if ($instance instanceof \LegacyRS\Controller\LegacyRSController) {
                    $instance->addServerSideAnalytics($sm->getServiceLocator()->get('DaleyPiwik\Service\PhpTracker'));
                }
            },
        ),
    ),
);
