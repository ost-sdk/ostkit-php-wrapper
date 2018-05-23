<?php
namespace Ostkit;
use Curl\Curl as Curl;

class Ostkit
{
    public $apiUrl = 'https://sandboxapi.ost.com/v1';
    public $apiKey = '';
    public $apiSecret = '';

    protected $curl = '';

    public function __construct($apiKey = '', $apiSecret = '', $apiUrl = '')
    {
        if ($apiUrl != '') {
            $this->apiUrl = $apiUrl;
        }
        if ($apiKey != '') {
            $this->apiKey = $apiKey;
        }
        if ($apiSecret != '') {
            $this->apiSecret = $apiSecret;
        }
        $this->curl = new Curl();
    }

    public function userCreate($name)
    {
        $endPoint = $this->getEndPoint('users');
        $inputParams = [
          'name'=> $name
        ];
        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        
        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    public function userEdit($uuid, $name)
    {
        $endPoint = $this->getEndPoint('users') . '/' . $uuid;

        $inputParams = [
            'name'=> $name
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);

        return json_decode($result->response, true);
    }

    //https://dev.ost.com/docs/api_actions_retrieve.html
    public function userRetrieve($uuid)
    {
        $endPoint = $this->getEndPoint('users') . '/' . $uuid;

        $inputParams = [
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);

        return json_decode($result->response, true);
    }

    public function userList($page_no = 1, $airdropped = true, $order_by ='name', $order = 'desc', $optional__filters = 'all', $limit = 10)
    {
        $endPoint = $this->getEndPoint('users');
        $inputParams = [
            'page_no' => $pageNo,
            // 'airdropped' => $airdropped,
            'order_by' => $orderBy,
            'order' => $order,
            'limit' => $limit,
            'optional__filters' => $optional__filters
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    public function transactionCreate($name, $kind, $currency_value, $commission_percent, $currency_type = 'USD')
    {
        $endPoint = $this->getEndPoint('transactionCreate');
        $inputParams = [
            'name' => $name,
            'kind' => $kind,
            'currency_type' => $currency_type,
            'currency_value' => $currency_value,
            'commission_percent' => $commission_percent
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    public function transactionEdit($client_transaction_id, $name, $kind, $currency_value, $commission_percent, $currency_type = 'USD')
    {
        $endPoint = $this->getEndPoint('transactionEdit');
        $inputParams = [
            'client_transaction_id' => $client_transaction_id,
            'name' => $name,
            'kind' => $kind,
            'currency_type' => $currency_type,
            'currency_value' => $currency_value,
            'commission_percent' => $commission_percent
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    public function transactionList()
    {
        $endPoint = $this->getEndPoint('transactionList');
        $inputParams = [];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    public function transactionExec($from_uuid, $to_uuid, $transaction_kind)
    {
        $endPoint = $this->getEndPoint('transactionExec');
        $inputParams = [
            'from_uuid' => $from_uuid,
            'to_uuid' => $to_uuid,
            'transaction_kind' => $transaction_kind
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    public function transactionStatus($transaction_uuids = array())
    {
        $endPoint = $this->getEndPoint('transactionStatus');
        $inputParams = [
            'transaction_uuids' => $transaction_uuids,
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        
        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    protected function getEndPoint($name)
    {
        $endPoint = '';
        switch ($name) {
            case 'users':
                $endPoint = '/users';
                break;
            case 'transactionCreate':
                $endPoint = '/transaction-types/create';
                break;
            case 'transactionEdit':
                $endPoint = '/transaction-types/edit';
                break;
            case 'transactionList':
                $endPoint = '/transaction-types/list';
                break;
            case 'transactionExec':
                $endPoint = '/transaction-types/execute';
                break;
            case 'transactionStatus':
                $endPoint = '/transaction-types/status';
                break;
        }
        return $endPoint;
    }


    protected function generateRequestParams($endPoint, $inputParams, $signature, $requestTimestamp)
    {
        $inputParams['api_key'] = $this->apiKey;
        $inputParams['request_timestamp'] = $requestTimestamp;
        $inputParams['signature'] = $signature;
        return array(
            'requestURL' => $this->apiUrl . $endPoint,
            'inputParams' => $inputParams
        );
    }

    protected function generateQueryString($endPoint, $inputParams, $apiKey, $requestTimestamp)
    {
        $inputParams["api_key"] = $apiKey;
        $inputParams["request_timestamp"] = $requestTimestamp;
        ksort($inputParams);
        $stringToSign = $endPoint . '?' . http_build_query($inputParams);
        // $stringToSign = str_replace('%20', '+', $stringToSign);
        return $stringToSign;
    }

    protected function generateApiSignature($stringToSign, $apiSecret)
    {
        $hash = hash_hmac('sha256', $stringToSign, $apiSecret);
        return $hash;
    }

    protected function getRequestParams($endPoint, $inputParams)
    {
        $requestTimestamp = time();

        $queryString = $this->generateQueryString(
            $endPoint,
            $inputParams,
            $this->apiKey,
            $requestTimestamp
        );

        $signature = $this->generateApiSignature(
            $queryString,
            $this->apiSecret
        );
        $requestParams = $this->generateRequestParams($endPoint, $inputParams, $signature, $requestTimestamp);
        return $requestParams;
    }
}
   