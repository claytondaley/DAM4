<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 1/4/2015
 * Time: 5:28 PM
 */

namespace DAM4\Listener;

use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;

class ZfcUserListener implements SharedListenerAggregateInterface
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
        $this->listeners[] = $sharedManager->attach('ZfcUser\Authentication\Adapter\AdapterChain', 'authenticate.success', array($this, 'setupSession'));
        $this->listeners[] = $sharedManager->attach('ZfcUser\Service\User', 'register', array($this, 'initializeRsUser'));
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
     * @param EventInterface $event
     */
    public function setupSession(EventInterface $event)
    {
        /*
         * From legacy:
         *
         * # Enable the 'keep me logged in at this workstation' option at the login form
         * # If the user then selects this, a 100 day expiry time is set on the cookie.
         * $allow_keep_logged_in= true;
         * #Remember Me Checked By Default
         * $remember_me_checked = true;
         */

        # pull in settings
        # include(getcwd() . '/vendor/resourcespace/resourcespace/include/config.default.php');
        # include(getcwd() . '/vendor/resourcespace/resourcespace/include/config.php');

        $token = base64_encode( openssl_random_pseudo_bytes(32) );
        $cookieLength = 86400;

        # Store in Cookie
        ####################### NEED TO CHANGE SECURE PARAM TO TRUE ######################
        setrawcookie("user", $token, time() + $cookieLength, '/', null, false, true);
        ####################### NEED TO CHANGE SECURE PARAM TO TRUE ######################
    }

    /**
     * This function replicates legacy user registration logic for users created using ZfcUser. See
     * pages/team/team_user_edit.php for actual user save code.
     *
     * @param EventInterface $event
     */
    public function initializeRsUser(EventInterface $event)
    {
        # get user object from ZfcUser
        $user = $event->getParam('user');

        # pull in settings
        include(getcwd() . '/resourcespace/resourcespace/include/config.default.php');
        include(getcwd() . '/resourcespace/resourcespace/include/config.php');

        # Register logic goes here

        # If the usergroup is null, the user was not created using the admin page
        if ($user->getUsergroup == null) {

            # Set the state based on the auto approval configuration
            /** @noinspection PhpUndefinedVariableInspection */
            $user->setState($auto_approve_accounts);

            # Set the usergroup to the configured default
            /** @noinspection PhpUndefinedVariableInspection */
            $user->setUsergroup($default_group);

        }
    }
}