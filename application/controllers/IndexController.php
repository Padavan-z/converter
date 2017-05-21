<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        MyCache::getInstance();
    }

    public function indexAction()
    {
        // action body
        $currency  = new Application_Model_Currency();
        $this->view->defaultCurrency = $currency->defaultCurrency;
        $this->view->currencyList = $currency ->currencyList;
    }


}

