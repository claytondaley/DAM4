<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/12/2015
 * Time: 4:08 PM
 */

namespace DAM4\Listener;

use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

class MvcEventListener implements SharedListenerAggregateInterface
{
    protected $manager = null;
    protected $listeners = array();

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the SharedEventManager
     * implementation will pass this to the aggregate.
     *
     * @param SharedEventManagerInterface $sharedManager
     */
    public function attachShared(SharedEventManagerInterface $sharedManager)
    {
        $this->listeners[] = $sharedManager->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($this, 'ajaxLayout'));
    }

    /**
     * Detach all previously attached listeners
     *
     * @param SharedEventManagerInterface $events
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detachShared($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * To provide the login state to the legacy applicaiton, we need to store a token in both the cookies (as 'user')
     * and the database.  This event handler ensures that a cookie is set if a user is logged in successfully. We need
     * to do this now to make sure it's available for a subsequent page load. For efficiency and simplicity, we defer
     * storing the token in the database until a legacy page is actually being loaded
     *
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function ajaxLayout(\Zend\Mvc\MvcEvent $e)
    {
        if ($e->getRequest()->getQuery('ajax', false) === "true") {
            // Set the layout template
            $viewModel = $e->getViewModel();
            $viewModel->setTemplate('layout/ajax');
        }
    }
}