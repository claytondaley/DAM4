<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace DAM4\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * Quick and dirty way to redirect unauthroized users to a login screen.
     */
    public function redirectAction()
    {
        // get new route from route parameter
        $route = $this->params()->fromRoute('route');

        // add redirect query if the route is login
        $query = array();
        if ($route == 'zfcuser/login') {
            $query['redirect'] = $this->getRequest()->getUri();
        }

        if ($this->getRequest()->getQuery('ajax', false) === "true") {
            $query['ajax'] = "true";
        }

        // move username from ref to userId if route is user (admin) edit
        $params = array();
        if ($route == 'zfcadmin/zfcuseradmin/edit') {
            $params['userId'] = $this->params()->fromQuery('ref');
        }

        // respond with redirect
        return $this->redirect()->toRoute(
            $route,
            $params,
            array( #options
                'query' => $query
            )
        );
    }

    /**
     * Legacy users a query string to distinguish between a logout and login.  ZF2 doesn't like routing by query string
     * so this action has been added to check and correctly route the hijacked (legacy) calls.
     *
     * @param bool $logout
     * @return Response
     */
    public function loginAction($logout = false)
    {
        if ($logout or $this->params()->fromQuery('logout') == "true") {
            $route = 'zfcuser/logout';
        } else {
            $route = 'zfcuser/login';
        }

        // add redirect query if the route is login
        $query = Array();
        if ($route == 'zfcuser/login' and $this->params()->fromQuery('redirect')) {
            $query['redirect'] = $this->params()->fromQuery('redirect');
        }

        // respond with redirect
        return $this->redirect()->toRoute(
            $route,
            Array(),
            Array('query' => $query)
        );
    }
}
