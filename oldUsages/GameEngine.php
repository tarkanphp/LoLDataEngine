<?php
require 'Curly.php';
try {
        $conn = new PDO("mysql:host=localhost;dbname=LoLDataBase", "root", "");
        $conn->exec("SET NAMES utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
catch (PDOException $e) 
    {
        die($e->getMessage());
    }
$server = 'tr1.';
//$account_id = '71hWw-qbbO0_WXoD1FG23pOtKg_ch-m8wJelM9HceKpeQBA';
$api_key = '';
$query="SELECT * FROM discover WHERE done=:done ORDER BY idds LIMIT 0,1";
    $query_parameters=[
        'done'=>0
    ];
    $queryrun=$conn->prepare($query);
    $queryrun->execute($query_parameters);
    $result = $queryrun->fetchAll(PDO::FETCH_OBJ);
    $result=$result[0];
    $account_id=$result->id;
/////////////////////////////////**** GAME ID FOREACH ****////////////////////////////////////////////////////////////////////////////  

$url = 'https://' . $server . 'api.riotgames.com/lol/summoner/v4/summoners/by-account/' . $account_id . '?api_key=' . $api_key;
$rest = new Curly();
$rest->get($url);
$user_data = null;
$account_icon = null;
$games=[];
$mhistory_url = 'https://' . $server . 'api.riotgames.com/lol/match/v4/matchlists/by-account/' . $account_id . '?api_key=' . $api_key;
$rest->get($mhistory_url);
if ($rest->response_code == 200) {
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
?>