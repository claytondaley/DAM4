<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 4/4/2015
 * Time: 8:05 PM
 */

namespace DAM4\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\Navigation\Page\AbstractPage;
use ZfcRbac\Service\AuthorizationServiceInterface;

class RbacNavigation implements SharedListenerAggregateInterface
{
    /**
     * @var AuthorizationServiceInterface
     */
    protected $authorizationService;
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
        $this->listeners[] = $sharedManager->attach(
            'Zend\View\Helper\Navigation\AbstractHelper',
            'isAllowed',
            array($this, 'accept')
        );
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
     * @param AuthorizationServiceInterface $authorizationService
     */
    public function __construct(AuthorizationServiceInterface $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * @param  EventInterface $event
     * @return bool|void
     */
    public function accept(EventInterface $event)
    {
        $page = $event->getParam('page');

        if (!$page instanceof AbstractPage) {
            return;
        }

        $permission = $page->getPermission();

        if (null === $permission) {
            return;
        }

        $event->stopPropagation();

        return $this->authorizationService->isGranted($permission);
    }
}