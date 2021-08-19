<?php
require 'connection.php';
require 'Curly.php';
$api_key = 'RGAPI-d612e4d1-20e2-4047-9890-d13fef36ee5f';
$server = 'tr1.';
$rest = new Curly();
$query="SELECT * FROM discover_v2 WHERE done=:done ORDER BY id LIMIT 0,1";
$done=[
    'done'=>0
];
$queryrun=$conn->prepare($query);
$queryrun->execute($done);
$result = $queryrun->fetchAll(PDO::FETCH_OBJ);
$result=$result[0];
$account_id=$result->accountId;
$match_history=null;
$url = 'https://' . $server . 'api.riotgames.com/lol/match/v4/matchlists/by-account/' . $account_id . '?api_key=' . $api_key;
$rest->get($url);
if($rest->response_code==200)
{
    $match_history= json_decode($rest->response_data,true);
    foreach ($match_history["matches"] as $key => $match) 
    { 
        $query="select * from game_v2 where gameId=:gameId";
        $stmt = $conn->prepare($query);
        $result_check= $stmt->execute([":gameId"=>$match["gameId"]]);
        if($stmt->rowCount()==0)
        {
            $stmt = $conn->prepare("INSERT INTO game_v2 (gameId, accountId) VALUES (:gameId,:acc_id)");
            $result1 = $stmt->execute(array(":gameId" => $match["gameId"],":acc_id" => $account_id));
        }
    }
}