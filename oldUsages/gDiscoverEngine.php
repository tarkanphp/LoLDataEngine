<?php
try {
        $conn = new PDO("mysql:host=localhost;dbname=LoLDataBase", "root", "");
        $conn->exec("SET NAMES utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
catch (PDOException $e) 
    {
        die($e->getMessage());
    }
require 'Curly.php';
$server = 'tr';
$server = $server . "1.";
$api_key = 'RGAPI-d612e4d1-20e2-4047-9890-d13fef36ee5f';
$query="SELECT * FROM discover WHERE done=:done ORDER BY idds LIMIT 0,1";
    $query_parameters=[
        'done'=>0,
    ];
$queryrun=$conn->prepare($query);
$queryrun->execute($query_parameters);
$result = $queryrun->fetchAll(PDO::FETCH_OBJ);
$result=$result[0];
$account_id=$result->id;
$rest= new Curly();
$mhistory_url = 'https://' . $server . 'api.riotgames.com/lol/match/v4/matchlists/by-account/' . $account_id . '?api_key=' . $api_key;
$rest->get($mhistory_url);
if ($rest->response_code == 200) 
{
    $history_data = json_decode($rest->response_data, true);

    foreach ($history_data["matches"] as $historykey => $history) 
    {
        $games[] = [
            "gameId" => $history["gameId"],
        ];
        $query="select * from game where gameId=:gameId";
        $stmt = $conn->prepare($query);
        $result_check= $stmt->execute([":gameId"=>$history["gameId"]]);
        if($stmt->rowCount()==0)
        {
            $stmt = $conn->prepare("INSERT INTO game (GameId, acc_id) VALUES (:gameId,:acc_id)");
            $result1 = $stmt->execute(array(":gameId" => $history["gameId"],":acc_id" => $account_id));
        }
        else
        {
            echo $history["gameId"]." => eklenmedi<br />";
        }
    }
}