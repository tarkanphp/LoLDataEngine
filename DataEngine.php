<?php

require('Api.php');

class DataEngine
{
    protected $dbModel;
    protected $api;
    protected $url;
    protected $apiKey;
    protected $appId;

    public function __construct() {
        $this->setConfigurations();
    }

    public function setConfigurations() {
        try{
            (new SetEnv())->load();

            $this->dbModel = new Model();
            $this->api = new Api();

            $this->apiKey = '?api_key='.getenv('API_KEY');
            $this->appId  = getenv('APP_ID');
            $this->url = "https://tr1.api.riotgames.com/lol/%s";
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function testRequest() {
        $summonerId = "IVR3ZplmnBPfrhNf6xf3jYX3ECh9Y-7KpRSCxBlsmWwGqMU/";
        $endPoint = sprintf("summoner/v4/summoners/%s%s", $summonerId, $this->apiKey);
        $url = sprintf($this->url, $endPoint);
        try{
            $response = $this->api->sendRequest("get", $url);
            if($response->status) {
                $data = json_decode($response->responseData);
                echo "<pre>";
                var_dump($data); exit();
                if(empty($data)) {
                    throw new Exception("RIOT API_CLIENT ERROR : EMPTY RESPONSE");
                }
                return $this->dbModel->insertIntoSummoners($data);
            }
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }
}

$b = (new DataEngine())->testRequest();
echo "<pre>";
var_dump($b);
exit("<br> done");