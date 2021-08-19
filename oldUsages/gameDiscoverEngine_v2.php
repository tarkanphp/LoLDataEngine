<?php
require 'connection.php';
require 'Curly.php';
$api_key = 'RGAPI-d612e4d1-20e2-4047-9890-d13fef36ee5f';
$server = 'tr1.';
$rest = new Curly();
////-----<Sevan Hoca>----
//$query="SELECT accountId, SUM(checker) AS checker FROM 
//		(SELECT accountId,1 AS checker FROM game_v2 WHERE done=:done) AS game_data 
//	GROUP BY accountId";
//    $done=[
//        'done'=>1,
//    ];
//$queryrun=$conn->prepare($query);
//$queryrun->execute($done);
//$result_complition = $queryrun->fetchAll(PDO::FETCH_OBJ);
//
//
//foreach ($result_complition as $game) {
//    
//}
////-----</Sevan Hoca>----
$query="SELECT * FROM game_v2 WHERE done=:done ORDER BY id";
    $done=[
        'done'=>0,
    ];
$queryrun=$conn->prepare($query);
$queryrun->execute([":done"=>$done["done"]]);
$result = $queryrun->fetchAll(PDO::FETCH_OBJ);
$gameCount=count($result);
foreach ($result as $key => $value) {
}
$account_id=$result[$key]->accountId;
    if($gameCount==1)
    {
        $account_id=$result[0]->accountId;
        $stmt = $conn->prepare("UPDATE discover_v2 SET done=:done WHERE accountId=:accountId");
        $result_check= $stmt->execute([":accountId"=>$account_id,":done"=>1]);
        echo "done 1 olmuştur";
    }
    