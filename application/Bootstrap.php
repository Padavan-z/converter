<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public function _initRoute() {

        try {
            /* Get default route */
            $router = Zend_Controller_Front::getInstance()->getRouter();

            /* Set action for POST requests */
            $routeAjax = new Zend_Controller_Router_Route_Regex(
                '(ajax)',
                [
                    'controller' => 'ajax',
                    'action' => 'ajax'
                ]
            );

            $router->addRoute('postsCall', $routeAjax);
        } catch (Exception $e) {
            var_dump($e);
        }

    }
}

