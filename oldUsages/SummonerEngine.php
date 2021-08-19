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
//$account_id = '71hWw-qbbO0_WXoD1FG23pOtKg_ch-m8wJelM9HceKpeQBA';
$api_key = 'RGAPI-d612e4d1-20e2-4047-9890-d13fef36ee5f';
//Hesap Bağlantısı
    $query_2="SELECT * FROM game WHERE done=:done ORDER BY Games LIMIT 0,1";
        $query_done=[
            'done'=>0,
        ];
    $queryrun_2=$conn->prepare($query_2);
    $queryrun_2->execute($query_done);
    $result_2 = $queryrun_2->fetchAll(PDO::FETCH_OBJ);
    $result_2=$result_2[0];
    $gameIds=$result_2->GameId;
    $match_url = 'https://' . $server . 'api.riotgames.com/lol/match/v4/matches/' . $gameIds . '?api_key=' . $api_key;
    $rest= new Curly();
    $rest->get($match_url);
    $match_info = null;
    if ($rest->response_code == 200) {
        $gameDetail = json_decode($rest->response_data, true);
        foreach ($gameDetail["participantIdentities"] as $gamekey => $participantIdentity) {
            $participants[] = [
                "summonerName" => $participantIdentity["player"]["summonerName"],
                "summonerId" => $participantIdentity["player"]["summonerId"],
                "accountId" => $participantIdentity["player"]["accountId"],
                "matchHistoryUri" => $participantIdentity["player"]["matchHistoryUri"]
            ];
            $query="select * from discover where id=:acc_id";
            $stmt_2 = $conn->prepare($query);
            $result_check= $stmt_2->execute([":acc_id"=>$participants[$gamekey]["accountId"]]);
            if($stmt_2->rowCount()==0)
            {
                $stmt_2 = $conn->prepare("INSERT INTO discover (id, summoner_name, parent) VALUES (:id, :summoner_name, :parent)");
                $result2 = $stmt_2->execute(array(":id" => $participants[$gamekey]["accountId"], ":summoner_name" => $participants[$gamekey]["summonerName"], ":parent" => $account_id));
            }
            
            $g_query="update game set done=1 where gameId=:gameId";
            $stmt = $conn->prepare($g_query);
            $result_check_2= $stmt->execute([":gameId"=>$gameIds]);
        }
    
}
?>