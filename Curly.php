<?php

class Curly {
    public $response;
    public $responseCode;
    public $responseData;
    public $success;
    public $apiHeaders;

    /**
     * Curly constructor.
     */
    public function __construct() {
        $this->setApiHeaders();
    }

    /**
     * @param string $data_string
     * @param array $extraHeaders
     */
    public function setApiHeaders($dataString = "", $extraHeaders = array()) {
         $this->apiHeaders = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($dataString));

         if(!empty($extraHeaders)) {
             foreach($extraHeaders as $header) {
                 array_push($this->apiHeaders, $header);
             }
         }
    }

    /**
     * @param $method
     * @param $url
     * @param array $dataString
     * @param array $extraHeaders
     * @return mixed
     * @throws Exception
     */
    public function methodHandler($method, $url, $dataString = "", $extraHeaders = array()) {

        try{
            if(method_exists(__CLASS__, $method)) {
                $this->$method($url, $dataString, $extraHeaders);
                return $this->getResponse();
            }else{
                throw new Exception(sprintf("Undefined function to use : <b>%s</b>", $method));
            }
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $url
     * @param string $dataString
     * @param array $extraHeaders
     */
    public function get($url, $dataString = "", $extraHeaders = array()) {
        $this->setApiHeaders($dataString, $extraHeaders);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->apiHeaders);

        $result = curl_exec($ch);
        $this->setResponse(curl_getinfo($ch, CURLINFO_HTTP_CODE), $result);
    }

    /**
     * @param $url
     * @param $dataString
     * @param array $extraHeaders
     */
    public function post($url, $dataString, $extraHeaders = array()) {
        $this->setApiHeaders($dataString, $extraHeaders);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->apiHeaders);

        $result = curl_exec($ch);
        $this->setResponse(curl_getinfo($ch, CURLINFO_HTTP_CODE), $result);
    }

    /**
     * @param $url
     * @param $dataString
     * @param array $extraHeaders
     */
    public function put($url, $dataString, $extraHeaders = array()) {
        $this->setApiHeaders($dataString, $extraHeaders);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->apiHeaders);

        $result = curl_exec($ch);
        $this->setResponse(curl_getinfo($ch, CURLINFO_HTTP_CODE), $result);
    }

    /**
     * @param $url
     * @param $dataString
     * @param array $extraHeaders
     */
    public function delete($url, $dataString, $extraHeaders = array()) {
        $this->setApiHeaders($dataString, $extraHeaders);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->apiHeaders);

        $result = curl_exec($ch);
        $this->setResponse(curl_getinfo($ch, CURLINFO_HTTP_CODE), $result);
    }

    /**
     * @param $responseCode
     * @param $responseData
     */
    public function setResponse($responseCode, $responseData) {
        $this->responseCode = $responseCode;
        $this->responseData = $responseData;
        $this->success      = $responseCode == 200 ? true : false;

        $this->response = new stdClass();
        $this->response->status       = $this->success;
        $this->response->responseCode = $this->responseCode;
        $this->response->responseData = $this->responseData;
    }

    /**
     * @return mixed
     */
    public function getResponse() {
        return $this->response;
    }
}

/*switch ($method) {
    case 'get':
        $this->get($url, $dataString, $extraHeaders);
        break;
    case 'post':
        $this->post($url, $dataString, $extraHeaders);
        break;
    case 'put':
        $this->put($url, $dataString, $extraHeaders);
        break;
    case 'delete':
        $this->delete($url, $dataString, $extraHeaders);
        break;
    default:
        throw new Exception(sprintf("Undefined method to use : <b>%s</b>", $method));
}*/