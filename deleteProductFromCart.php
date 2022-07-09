<?php

include "connect.php";

$json = file_get_contents("php://input");
$cartDetails = json_decode($json);
$cartId = $cartDetails->cartId;

$updateSql = "DELETE FROM cart WHERE id=:cartId";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bindParam(':cartId', $cartId);
$updateResult = $updateStmt->execute();