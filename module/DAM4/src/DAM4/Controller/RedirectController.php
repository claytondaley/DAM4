<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/12/2015
 * Time: 8:41 PM
 */

namespace DAM4\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class RedirectController extends AbstractActionController
{
    /**
     * Quick and dirty way to hijack LegacyRS pages.
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
}
