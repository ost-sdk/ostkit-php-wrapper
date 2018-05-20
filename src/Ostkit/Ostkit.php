<?php
namespace Ostkit;
use Curl\Curl as Curl;

class Ostkit
{
    public $apiUrl = 'https://playgroundapi.ost.com';
    public $apiKey = '';
    public $apiSecret = '';



    public function __construct($apiUrl = '', $apiKey = '', $apiSecret = '')
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
    }

    public function userCreate($name)
    {
        $endPoint = '/users/create';

        $inputParams = [
          'name'=> $name
        ];
        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        $curl = new Curl();
        $result = $curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    public function userEdit($uuid, $name)
    {
        $endPoint = '/users/edit';

        $inputParams = [
            'uuid'=> $uuid,
            'name'=> $name
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        $curl = new Curl();
        $result = $curl->post($requestParams['requestURL'], $requestParams['inputParams']);

        return json_decode($result->response, true);
    }

    //https://dev.ost.com/docs/api_actions_retrieve.html
    public function userRetrieve($uuid)
    {
        $endPoint = '/users/' . $uuid;

        $inputParams = [
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        $curl = new Curl();
        $result = $curl->get($requestParams['requestURL'], $requestParams['inputParams']);

        return json_decode($result->response, true);
    }

    public function userList($pageNo = 1, $filter = 'all', $orderBy ='name', $order = 'desc')
    {
        $endPoint = '/users/list';
        $inputParams = [
            'page_no' => $pageNo,
            'filter' => $filter,
            'order_by' => $orderBy,
            'order' => $order,
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $curl = new Curl();
        $result = $curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    public function transactionCreate($name, $kind, $currency_value, $commission_percent, $currency_type = 'USD')
    {
        $endPoint = '/transaction-types/create';
        $inputParams = [
            'name' => $name,
            'kind' => $kind,
            'currency_type' => $currency_type,
            'currency_value' => $currency_value,
            'commission_percent' => $commission_percent
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $curl = new Curl();
        $result = $curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    public function transactionEdit($client_transaction_id, $name, $kind, $currency_value, $commission_percent, $currency_type = 'USD')
    {
        $endPoint = '/transaction-types/edit';
        $inputParams = [
            'client_transaction_id' => $client_transaction_id,
            'name' => $name,
            'kind' => $kind,
            'currency_type' => $currency_type,
            'currency_value' => $currency_value,
            'commission_percent' => $commission_percent
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $curl = new Curl();
        $result = $curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    public function transactionList()
    {
        $endPoint = '/transaction-types/list';
        $inputParams = [];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $curl = new Curl();
        $result = $curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    public function transactionExec($from_uuid, $to_uuid, $transaction_kind)
    {
        $endPoint = '/transaction-types/execute';
        $inputParams = [
            'from_uuid' => $from_uuid,
            'to_uuid' => $to_uuid,
            'transaction_kind' => $transaction_kind
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $curl = new Curl();
        $result = $curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    public function transactionStatus($transaction_uuids = array())
    {
        $endPoint = '/transaction-types/status';
        $inputParams = [
            'transaction_uuids' => $transaction_uuids,
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        
        $curl = new Curl();
        $result = $curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    public function generateRequestParams($endPoint, $inputParams, $signature, $requestTimestamp)
    {
        $inputParams['api_key'] = $this->apiKey;
        $inputParams['request_timestamp'] = $requestTimestamp;
        $inputParams['signature'] = $signature;
        return array(
            'requestURL' => $this->apiUrl . $endPoint,
            'inputParams' => $inputParams
        );
    }

    public function generateQueryString($endPoint, $inputParams, $apiKey, $requestTimestamp)
    {
        $inputParams["api_key"] = $apiKey;
        $inputParams["request_timestamp"] = $requestTimestamp;
        ksort($inputParams);
        $stringToSign = $endPoint . '?' . http_build_query($inputParams);
        // $stringToSign = str_replace('%20', '+', $stringToSign);
        return $stringToSign;
    }

    public function generateApiSignature($stringToSign, $apiSecret)
    {
        $hash = hash_hmac('sha256', $stringToSign, $apiSecret);
        return $hash;
    }

    public function getRequestParams($endPoint, $inputParams)
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
   