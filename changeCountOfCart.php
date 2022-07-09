<?php

include "connect.php";

$json = file_get_contents("php://input");
$cartDetails = json_decode($json);
$cartId = $cartDetails->cartId;
$count = $cartDetails->count;

if(0<$count && $count<6){
    $updateSql = "UPDATE cart SET count=:count WHERE id=:cartId";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':cartId', $cartId);
    $updateStmt->bindParam(':count', $count);
    $updateResult = $updateStmt->execute();
}

