<?php

require('SetEnv.php');

/**
 * Class Model
 */
class Model
{
    /**
     * @var false|PDO
     */
    protected $db;

    /**
     * Model constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->setConfigurations();
    }

    public function insertIntoSummoners($dataArray) {
        if($this->checkSummonerExists($dataArray->id)) {
            throw new Exception("Already registered");
        }
        $insertData = array(
            $dataArray->id,
            $dataArray->accountId,
            $dataArray->puuid,
            $dataArray->name
        );
        $sql = "INSERT INTO summoners (summonerId, accountId, puuId, name)
                VALUES (?, ?, ?, ?)";
        $this->db->beginTransaction();
        $query = $this->db->prepare($sql);
        $query->execute($insertData);
        $this->db->commit();

        return $this->db->lastInsertId();
    }

    public function checkSummonerExists($summonerId) {
        $query = $this->db->prepare("SELECT count(id) AS 'rowCount' FROM summoners WHERE summonerId = ? LIMIT 1");
        $query->execute(array($summonerId));

        $rowCount = $query->fetch();

        return ($rowCount['rowCount'] > 0) ? true : false;
    }

    /**
     * @param PDO $databaseConnection
     * @throws Exception
     */
    private function setDatabase(PDO $databaseConnection) {
        if(!$databaseConnection instanceof PDO) {
            throw new Exception("An error happened while setting database configurations");
        }
        $this->db = $databaseConnection;
    }

    /**
     * @throws Exception
     */
    private function setConfigurations() {
        try{
            /**
             * Having the db datum from .env file
             */
            (new SetEnv())->load();
            $host     = getenv('DB_HOST');
            $username = getenv('DB_USER');
            $password = getenv('DB_PASSWORD');
            $database = getenv('DB_NAME');
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        try{
            /**
             * Testing our db connection
             */
            new mysqli($host, $username, $password, $database);
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        try {
            $dbConnection = new PDO(sprintf("mysql:host=%s;dbname=%s", $host, $database), $username, $password);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            /**
             * We attribute our PDO class to public db variable to use queries or etc...
             */
            $this->setDatabase($dbConnection);
        }
        catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}