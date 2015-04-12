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
use Zend\Mvc\Router\RouteMatch;

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
        // If the url includes `ajax=true`, we need to prevent the standard layout from rendering.
        $this->listeners[] = $sharedManager->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($this, 'ajaxLayout'));
        // If the "user" cookie is not set, the user will not show up as authenticted into the legacy app.  This cookie
        // is set during login so the easiest thing to do is to log them out of the ZF2 auth layer and force them to
        // log back in.
        $this->listeners[] = $sharedManager->attach('Zend\Mvc\Application', MvcEvent::EVENT_ROUTE, array($this, 'noCookieLogout'));
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
     * If the application request a URL with `ajax=true`, we need to hide the standard layout (menu, etc.).
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

    /**
     * Verify that the cookie is still in place and force the user to log back in if it is not.
     *
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function noCookieLogout(\Zend\Mvc\MvcEvent $e)
    {
        if ($e->getRouteMatch()->getParam('controller') == 'LegacyRS\Controller\Redirect' ||
            $e->getRouteMatch()->getParam('controller') == 'LegacyRS\Controller\LegacyRS')
        {
            # Check for auth cookie and redirect to logout if it doesn't exist
            if ($e->getRequest()->getCookie()->user === null) {
                $e->setRouteMatch(
                    new RouteMatch(array(
                        'controller'    => 'LegacyRS\Controller\Redirect', // Must match guards exactly
                        'action'        => 'default',
                        'name'          => 'logout',
                    ))
                );
                $e->getRouteMatch()->setMatchedRouteName('zfcuser\logout');
            }
        }
    }
}