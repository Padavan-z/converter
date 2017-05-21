<?php
/**
 * Created by PhpStorm.
 * User: padavan
 * Date: 18.05.17
 * Time: 19:30
 */

class Application_Model_Currency
{
    public $defaultCurrency = 'USD';

    public $currencyList = [
        "USD" => "USD", "AUD" => "AUD", "BGN" => "BGN", "BRL" => "BRL", "CAD" => "CAD", "CHF" => "CHF", "CNY" => "CNY",
        "CZK" => "CZK", "DKK" => "DKK", "GBP" => "GBP", "HKD" => "HKD", "HRK" => "HRK", "HUF" => "HUF", "IDR" => "IDR",
        "ILS" => "ILS" ,"INR" => "INR", "JPY" => "JPY", "KRW" => "KRW", "MXN" => "MXN", "MYR" => "MYR", "NOK" => "NOK",
        "NZD" => "NZD", "PHP" => "PHP", "PLN" => "PLN", "RON" => "RON", "RUB" => "RUB", "SEK" => "SEK", "SGD" => "SGD",
        "THB" => "THB", "TRY" => "TRY", "ZAR" => "ZAR", "EUR" => "EUR",
    ];

    public function getCurrencyConvert($params)
    {
        if ($this->validateParams($params)) {
            $key = $params['from'] . $params['to'];
            if (MyCache::getInstance()->test($key)) {
                $result = MyCache::getInstance()->load($key);
            } else {
                $result = $this->getRequestByCurl($params);
                MyCache::getInstance()->save($result, $key);
            }

            $result = json_decode($result, true);
            $convertRate = $result['rates'][$params['to']];
            $result = $this->calculateAmount($params['amount'], $convertRate);

            return $result;
        }
    }

    public function getRequestByCurl($params)
    {
        $urlParams = [
            'base' => $params['from'],
            'symbols' => $params['to'],
        ];
        $urlQuery = http_build_query($urlParams);

        $url = 'http://api.fixer.io/latest?' . $urlQuery;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function validateParams($params)
    {
        $result = false;
        if (
            isset($params['from'])
            && isset($params['to'])
            && isset($params['amount'])
            && in_array($params['from'], $this->currencyList)
            && in_array($params['to'], $this->currencyList)
            && $params['from'] != $params['to']
            && 0 < $params['amount']
        ) {
            $result = true;
        }

        return $result;
    }

    public function calculateAmount($amount, $convertRate)
    {
        $result = $amount * $convertRate;
        $result = number_format($result, 2, '.', '');

        return $result;
    }
}