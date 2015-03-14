<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/12/2015
 * Time: 8:41 PM
 */

namespace DAM4\Controller;

use Zend\Http\Response;
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

        if ($this->getRequest()->getQuery('ajax', false) === "true") {
            if ($route == 'zfcuser/logout') {
                // bust ajax
                /** @var Response $response */
                $response = $this->getResponse();
                $response->setStatusCode(Response::STATUS_CODE_200);
                $response->setContent('<script type="text/javascript">top.location.href="' .
                    $this->url()->fromRoute('zfcuser/logout') . '";</script>');
                return $response;
            } else {
                // preserve ajax
                $query['ajax'] = "true";
            }
        }

        if ($route == 'zfcuser/login') {
            //preserve redirect
            if (!$this->getRequest()->getQuery('redirect', false) === null) {
                $query['redirect'] = $this->getRequest()->getQuery('redirect');
            } else {
                $query['redirect'] = $this->getRequest()->getUri()->getPath();
            }
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
