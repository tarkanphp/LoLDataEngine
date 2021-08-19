<?php

require('Model.php');
require('Curly.php');

class Api
{
    /**
     * @var false|Curly
     */
    protected $curly;

    /**
     * Api constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->setConfigurations();
    }

    /**
     * @param $method
     * @param $url
     * @param array $dataArray
     * @param array $extraHeaders
     * @return mixed
     * @throws Exception
     */
    public function sendRequest($method, $url, $dataArray = "", $extraHeaders = array()) {
        $dataArrayNeededMethods = explode(',', getenv('DATA_STRING_NEEDED_METHODS'));

        if(in_array($method, $dataArrayNeededMethods) && empty($dataArray)) {
            throw new Exception(sprintf("Need dataArray to use method : %s", $method));
        }

        return $this->curly->methodHandler($method, $url, $dataArray, $extraHeaders);
    }

    /**
     * @throws Exception
     */
    protected function setConfigurations() {
        try{
            (new SetEnv())->load();
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        try{
            $this->curly = new Curly();
            if(!$this->curly instanceof Curly) {
                throw new Exception("An error happened while loading Curly class");
            }
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}