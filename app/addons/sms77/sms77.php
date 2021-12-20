<?php

use Tygh\Registry;

require_once Registry::get('config.dir.addons') . 'sms77/Utility.php';

class sms77 {
    private $apiKey;
    private $curl = false;
    private $debugMode;
    private $error;
    private $url = 'https://gateway.sms77.io/api/';

    /**
     * @throws Exception
     */
    public function __construct() {
        $apiKey = Utility::getApiKey();

        if (!$apiKey) {
            $err = __('sms77.missing_api_key');
            Utility::notifyAdmin('W', $err);

            throw new Exception($err);
        }

        $this->apiKey = $apiKey;
        $this->debugMode = defined('DEBUG_MODE') && DEBUG_MODE;
    }

    public function sendSMS(array $params, $finalize = '') {
        if ('' === $params['to']) {
            $this->error = 'missing_recipient';
            Utility::notifyAdmin('W', $this->error);
            return null;
        }

        $defaults = [
            'from' => function () {
                return Utility::getFrom();
            },
            'performance_tracking' => function () {
                return Utility::toBool($_POST['sms77_performance_tracking']);
            },
        ];

        foreach ($defaults as $k => $fn)
            if (!array_key_exists($k, $params)) $params[$k] = $fn();
        foreach ($params as $k => $v) if (is_bool($v)) $params[$k] = (int)$v;

        $response = $this->callAPI('sms', $params);

        if ('' !== $finalize) {
            $error = $this->getError();

            if ($error) {
                $info = $error;
                $status = 'fail';
                $notifyType = 'W';
                $notifyMessage = $error;
            } else {
                $info = $response;
                $status = 'success';
                $notifyType = 'N';
                $notifyMessage = 'sms_sent';
            }

            Utility::insertMessage(array_merge(compact('info', 'status'), [
                'text' => $params['text'],
                'to' => $params['to'],
                'type' => $finalize,
            ]));
            Utility::notifyAdmin($notifyType, $notifyMessage);
        }

        return $response;
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return false|int|mixed
     */
    private function callAPI($endpoint, array $params = []) {
        $this->error = null;
        $url = $this->url . $endpoint;

        foreach ($params as $k => $v) {
            if (is_bool($v)) $v = $v ? 1 : 0;
            $params[$k] = $v;
        }

        if ($this->curl) curl_close($this->curl);
        $this->curl = curl_init($url);

        $options = [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Connection: keep-alive',
                'Content-Type: application/json',
                'SentWith: CS-Cart',
                'X-Api-Key: ' . $this->apiKey,
            ],
            CURLOPT_RETURNTRANSFER => 1,
        ];

        if ($this->debugMode) $options[CURLINFO_HEADER_OUT] = 1;

        if (count($params)) $options[CURLOPT_POSTFIELDS] = json_encode($params);

        curl_setopt_array($this->curl, $options);

        $result = curl_exec($this->curl);

        if ($this->debugMode)
            fn_print_r($url, curl_getinfo($this->curl, CURLINFO_HEADER_OUT), $result);

        $result = false === $result ? false : json_decode($result);

        if (is_object($result)) {
            $prop = 'success';

            if (property_exists($result, $prop) && '100' !== $result->$prop)
                $this->error = $result->$prop;
        } elseif (is_int($result)) $this->error = (string)$result;
        else $this->error = $result;

        return $result;
    }

    public function getError() {
        return $this->error;
    }

    public function getBalance() {
        $balance = $this->callAPI('balance');

        return is_float($balance) ? $balance . ' €' : null;
    }
}
