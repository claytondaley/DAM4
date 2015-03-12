<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 1/18/2015
 * Time: 4:23 PM
 */

namespace DAM4\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

class RightMenuFactory extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'header_right';
    }
}