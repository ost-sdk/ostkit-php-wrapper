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

    /**
     * https://dev.ost.com/docs/api_users_create.html
     * @param string $name (name of the user (not unique))
     * @return array (follow ostkit document) 
     */
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

    /**
     * https://dev.ost.com/docs/api_users_edit.html
     * @param string $id (id of user) *
     * @param string $name (name of the user (not unique))
     * @return array (follow ostkit document) 
     */
    public function userEdit($id, $name)
    {
        $endPoint = $this->getEndPoint('users') . '/' . $id;

        $inputParams = [
            'name'=> $name
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);

        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_users_retrieve.html
     * @param string $id (id of user) *
     * @return array (follow ostkit document) 
     */
    public function userRetrieve($id)
    {
        $endPoint = $this->getEndPoint('users') . '/' . $id;

        $inputParams = [
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);
        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);

        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_users_list.html
     * @param number $page_no (page number (starts from 1))
     * @param boolean $airdropped (true == users who have been airdropped tokens, false == users who have not been airdropped tokens)
     * @param string $order_by ((optional) order the list by 'creation_time' or 'name' (default))
     * @param string $order ((optional) order users in 'desc' (default) or 'asc' order)
     * @param number $limit (limits the number of user objects to be sent in one request(min. 1, max. 100, default 10))
     * @param string $optional__filters (filters can be used to refine your list, the parameters on which filters are supported are detailed in the table below)
     *        @param string $id ('id="3b679b8b-b56d-48e5-bbbe-7397899c8ca6, d1c0be68-30bd-4b06-af73-7da110dc62da')
     *        @param string $name ('name="Alice, Bob"')
     * @return array (follow ostkit document) 
     */
    public function userList($page_no = 1, $airdropped = true, $order_by ='name', $order = 'desc', $limit = 10, $optional__filters = '')
    {
        $endPoint = $this->getEndPoint('users');
        $inputParams = [
            'page_no' => $page_no,
            // 'airdropped' => $airdropped,
            'order_by' => $order_by,
            'order' => $order,
            'limit' => $limit,
            'optional__filters' => $optional__filters
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    /**
     * https://dev.ost.com/docs/api_airdrop_execute.html
     * @param float $amount ((mandatory) The amount of BT that needs to be air-dropped to the selected end-users. Example:10)
     * @param boolean $airdropped (true/false. Indicates whether to airdrop tokens to end-users who have been airdropped some tokens at least once or to end-users who have never been airdropped tokens.)
     * @param string $user_ids (a comma-separated list of user_ids specifies selected users in the token economy to be air-dropped tokens to.)
     * @return array (follow ostkit document) 
     */
    public function airdropExec($amount, $airdropped, $user_ids)
    {
        $endPoint = $this->getEndPoint('airdrops');
        $inputParams = [
            'amount' => $amount,
            'airdropped' => $airdropped,
            'user_ids' => $user_ids
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_airdrop_retrieve.html
     * @param string $id (id of airdrop)
     * @return array (follow ostkit document) 
     */
    public function airdropRetrieve($id)
    {
        $endPoint = $this->getEndPoint('airdrops') . '/' . $id;
        $inputParams = [

        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_airdrop_list.html
     * @param number $page_no (page number (starts from 1))
     * @param string $order_by (order the list by when the airdrop was executed (default). Can only order by execution date.)
     * @param number $limit (limits the number of airdrop objects to be sent in one request. Possible Values Min 1, Max 100, Default 10.)
     * @param string $optional__filters (filters can be used to refine your list. The Parameters on which filters are supported are detailed in the table below.)
     * @return array (follow ostkit document) 
     */
    public function airdropList($page_no = 1, $order_by ='', $order = 'desc', $limit = 10, $optional__filters = '')
    {
        $endPoint = $this->getEndPoint('airdrops') . '/' . $id;
        $inputParams = [
            'page_no' => $page_no,
            'order_by' => $order_by,
            'order' => $order,
            'limit' => $limit,
            'optional__filters' => $optional__filters
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_actions_create.html
     * @param string $name ((mandatory) unique name of the action)
     * @param string $kind (an action can be one of three kinds: "user_to_user", "company_to_user", or "user_to_company" to clearly determine whether value flows within the application or from or to the company.)
     * @param string $currency ((mandatory) type of currency the action amount is specified in. Possible values are "USD" (fixed) or "BT" (floating). When an action is set in fiat the equivalent amount of branded tokens are calculated on-chain over a price oracle. For OST KIT⍺ price points are calculated by and taken from coinmarketcap.com and published to the contract by OST.com.)
     * @param boolean $arbitrary_amount ((mandatory) true/false. Indicates whether amount (described below) is set in the action, or whether it will be provided at the time of execution (i.e., when creating a transaction).)
     * @param string<float> $amount (amount of the action set in "USD" (min USD 0.01 , max USD 100) or branded token "BT" (min BT 0.00001, max BT 100). The transfer on-chain always occurs in branded token and fiat value is calculated to the equivalent amount of branded tokens at the moment of transfer.)
     * @param boolean $arbitrary_commission (true/false. Like 'arbitrary_amount' this attribute indicates whether commission_percent (described below) is set in the action, or whether it will be provided at the time of execution (i.e., when creating a transaction).)
     * @param string<float> $commission_percent (for user_to_user action you have an option to set commission percentage. The commission is inclusive in the amount and the percentage of the amount goes to the OST partner company. Possible values (min 0%, max 100%))
     * @return array (follow ostkit document) 
     */
    public function actionCreate($name, $kind, $currency, $arbitrary_amount, $amount, $arbitrary_commission = false, $commission_percent = 0)
    {
        $endPoint = $this->getEndPoint('actions');
        $inputParams = [
            'name' => $name,
            'kind' => $kind,
            'currency' => $currency,
            'arbitrary_amount' => $arbitrary_amount,
            'amount' => $amount,
            'arbitrary_commission' => $arbitrary_commission,
            'commission_percent' => $commission_percent
        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_actions_update.html
     * @param number $id (if of action)
     * @param arrray $params (parameter like create variable)
     * @return array (follow ostkit document) 
     */
    public function actionUpdate($id, $params)
    {
        $endPoint = $this->getEndPoint('actions') . '/' . $id;
        $inputParams = $params;

        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_actions_retrieve.html
     * @param number $id (if of action)
     * @return array (follow ostkit document) 
     */
    public function actionRetrieve($id)
    {
        $endPoint = $this->getEndPoint('actions') . '/' . $id;
        $inputParams = [

        ];

        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_actions_list.html
     * @param number $page_no (page number (starts from 1))
     * @param boolean $airdropped (true == users who have been airdropped tokens, false == users who have not been airdropped tokens)
     * @param string $order_by ((optional) order the list by 'creation_time' or 'name' (default))
     * @param string $order ((optional) order users in 'desc' (default) or 'asc' order)
     * @param number $limit (limits the number of user objects to be sent in one request(min. 1, max. 100, default 10))
     * @param string $optional__filters (filters can be used to refine your list, the parameters on which filters are supported are detailed in the table below)
     *        @param string $id ('id="20346, 20346"')
     *        @param string $name ('name="Like, Upvote"')
     *        @param string $kind ('kind="user_to_user"')
     *        @param string $arbitrary_amount ('arbitrary_amount= false')
     *        @param string $arbitrary_commission ('arbitrary_commission=true')
     * @return array (follow ostkit document) 
     */
    public function actionList($page_no = 1, $order_by ='name', $order = 'desc', $limit = 10, $optional__filters = '')
    {
        $endPoint = $this->getEndPoint('actions');
        $inputParams = [
            'page_no' => $page_no,
            'order_by' => $order_by,
            'order' => $order,
            'limit' => $limit,
            'optional__filters' => $optional__filters
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_action_execute.html
     * @param string $from_user_id (user or company from whom to send the funds)
     * @param string $to_user_id (user or company to whom to send the funds)
     * @param number $action_id (id of the action that is to be executed.)
     * @param string<float> $amount (amount of the action set in "USD" (min USD 0.01 , max USD 100) or branded token "BT" (min BT 0.00001, max BT 100). amount is set at execution when parameter arbitrary_amount is set to true while defining the action specified in action_id .)
     * @param string<float> $commission_percent (for a user_to_user action commission percentage is set at execution when parameter arbitrary_commission is set to true while defining the action specified in action_id . The commission is inclusive in the amount and the percentage commission goes to the OST partner company. Possible values (min 0%, max 100%))
     * @return array (follow ostkit document) 
     */
    public function transactionExec($from_uuid, $to_uuid, $action_id, $amount, $commission_percent)
    {
        $endPoint = $this->getEndPoint('transactions');
        $inputParams = [
            'from_uuid' => $from_uuid,
            'to_uuid' => $to_uuid,
            'action_id' => $action_id,
            'amount' => $amount,
            'commission_percent' => $commission_percent
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_transaction_retrieve.html
     * @param number $id (if of transaction)
     * @return array (follow ostkit document) 
     */
    public function transactionRetrieve($id)
    {
        $endPoint = $this->getEndPoint('transactions') . '/' . $id;
        $inputParams = [
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_transaction_list.html
     * @param number $page_no (page number (starts from 1))
     * @param boolean $airdropped (true == users who have been airdropped tokens, false == users who have not been airdropped tokens)
     * @param string $order_by (order the list by when the transaction was created (default) . Can only be ordered by transaction creation date.)
     * @param string $order ((optional) order users in 'desc' (default) or 'asc' order)
     * @param number $limit (limits the number of user objects to be sent in one request(min. 1, max. 100, default 10))
     * @param string $optional__filters (filters can be used to refine your list, the parameters on which filters are supported are detailed in the table below)
     *        @param string $id ('id="e1f95fcb-5853-453a-a9b3-d4f7a38d5beb, e7800825-fd24-4574-b7a6-06472ca1ef9d"')
     * @return array (follow ostkit document) 
     */
    public function transactionList($page_no = 1, $order_by ='', $order = 'desc', $limit = 10, $optional__filters = '')
    {
        $endPoint = $this->getEndPoint('transactions');
        $inputParams = [
            'page_no' => $page_no,
            'order_by' => $order_by,
            'order' => $order,
            'limit' => $limit,
            'optional__filters' => $optional__filters
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_transfers_create.html
     * @param hexstring $to_address ((mandatory) public address to which to transfer OST⍺ Prime)
     * @param number $amount ((mandatory) amount of OST⍺ Prime to transfer in Wei; should be between 0 and 10^20, exclusive)
     * @return array (follow ostkit document)
     */
    public function tranferCreate($to_address, $amount)
    {
        $endPoint = $this->getEndPoint('tranfers');
        $inputParams = [
            'to_address' => $to_address,
            'amount' => $amount
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->post($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_transfers_retrieve.html
     * @param number $id (if of transfer)
     * @return array (follow ostkit document) 
     */
    public function tranferRetrieve($id)
    {
        $endPoint = $this->getEndPoint('tranfers') . '/' . $id;
        $inputParams = [
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_transfers_list.html
     * @param number $page_no (page number (starts from 1))
     * @param boolean $airdropped (true == users who have been airdropped tokens, false == users who have not been airdropped tokens)
     * @param string $order_by (order the list by when the transaction was created (default) . Can only be ordered by transaction creation date.)
     * @param string $order ((optional) order users in 'desc' (default) or 'asc' order)
     * @param number $limit (limits the number of user objects to be sent in one request(min. 1, max. 100, default 10))
     * @param string $optional__filters (filters can be used to refine your list, the parameters on which filters are supported are detailed in the table below)
     *        @param string $id ('id="2c66960e-0380-4f7b-8f41-c344d44ab3d4, cee672d6-bd9f-4f41-a18c-81b651ea9393"')
     * @return array (follow ostkit document) 
     */
    public function tranferList($page_no = 1, $order_by ='', $order = 'desc', $limit = 10, $optional__filters = '')
    {
        $endPoint = $this->getEndPoint('tranfers');
        $inputParams = [
            'page_no' => $page_no,
            'order_by' => $order_by,
            'order' => $order,
            'limit' => $limit,
            'optional__filters' => $optional__filters
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }

    /**
     * https://dev.ost.com/docs/api_token.html
     * @return array (follow ostkit document) 
     */
    public function tokenDetail()
    {
        $endPoint = $this->getEndPoint('tokens');
        $inputParams = [
        ];
        
        $requestParams = $this->getRequestParams($endPoint, $inputParams);

        $result = $this->curl->get($requestParams['requestURL'], $requestParams['inputParams']);
        return json_decode($result->response, true);
    }


    protected function getEndPoint($name)
    {
        $endPoint = '';
        switch ($name) {
            case 'users':
                $endPoint = '/users';
                break;
            case 'actions':
                $endPoint = '/actions';
            case 'transactions':
                $endPoint = '/transactions';
                break;
            case 'tranfers':
                $endPoint = '/tranfers';
                break;
            case 'tokens':
                $endPoint = '/tokens';
                break;
            case 'airdrops':
                $endPoint = '/airdrops';
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
   