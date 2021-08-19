<?php

require 'connection.php';
require 'Curly.php';
$api_key = 'RGAPI-d612e4d1-20e2-4047-9890-d13fef36ee5f';
$server = 'tr1.';
$rest = new Curly();
$query = "SELECT * FROM game_v2 WHERE done=:done ORDER BY id LIMIT 0,1";
$done = [
    'done' => 0,
];
$queryrun = $conn->prepare($query);
$queryrun->execute($done);
$result = $queryrun->fetchAll(PDO::FETCH_OBJ);
$result1 = $result[0];
$gameId = $result1->gameId;
$match_data = null;
$match_url = 'https://' . $server . 'api.riotgames.com/lol/match/v4/matches/' . $gameId . '?api_key=' . $api_key;
$rest->get($match_url);
if ($rest->response_code == 200) {
    $match_data = json_decode($rest->response_data, true);
    foreach ($match_data["participantIdentities"] as $identity => $participant) 
    {
        $account_Ids[] = [
            "accountId" => $participant["player"]["accountId"]
        ];
        $query = "select * from discover_v2 where accountId=:acc_id";
        $stmt_2 = $conn->prepare($query);
        $result_check = $stmt_2->execute([":acc_id" => $account_Ids[$identity]["accountId"]]);
        if ($stmt_2->rowCount() == 0) {
            $stmt_2 = $conn->prepare("INSERT INTO discover_v2 (accountId, gameId) VALUES (:acc_id, :gameId)");
            $result2 = $stmt_2->execute(array(":acc_id" => $account_Ids[$identity]["accountId"], ":gameId" => $gameId));
        }
            $query="update game_v2 set done=1 where gameId=:gameId";
            $stmt = $conn->prepare($query);
            $result_check_2= $stmt->execute([":gameId"=>$gameId]);
    }
}