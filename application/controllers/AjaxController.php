<?php

class AjaxController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function ajaxAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $params = $this->getAllParams();
        if (isset($params['case'])) {
            switch ($params['case']) {
                case 'convert':
                    $this->result = $this->convert($params);
                    break;

                default:
                    $this->result = [
                        'status' => false,
                        'data' => '',
                        'error' => 'No case selected',
                    ];
                    break;
            }
        }
        header("Content-Type: text/json");
        echo json_encode($this->result);
    }

    public function convert($params)
    {
        $result = [
            'status' => false,
            'data' => '',
            'error' => '',
        ];
        try {
            $currency = new Application_Model_Currency();
            $res = $currency->getCurrencyConvert($params);
            $result = [
                'status' => true,
                'data' => $res,
                'error' => '',
            ];
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

}

