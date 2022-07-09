<?php

include "connect.php";

$json = file_get_contents("php://input");
$userDetails = json_decode($json);
$userId = $userDetails->userId;
$productId = $userDetails->productId;
$shoesSize = $userDetails->shoesSize;

$sql = "SELECT * FROM cart WHERE userId=:userId AND productId=:productId AND shoesSize=:shoesSize";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userId', $userId);
$stmt->bindParam(':productId', $productId);
$stmt->bindParam(':shoesSize', $shoesSize);
$stmt->execute();


if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $updateSql = "UPDATE cart SET count= count+1 WHERE userId=:userId AND productId=:productId AND shoesSize=:shoesSize";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':userId', $userId);
    $updateStmt->bindParam(':productId', $productId);
    $updateStmt->bindParam(':shoesSize', $shoesSize);
    $updateResult = $updateStmt->execute();
    if ($updateResult) {
        $message["message"] = "The product was added to the cart";
        echo json_encode($message);
    }
} else {
    $insertSql = "INSERT INTO cart (userId,productId,shoesSize) VALUE (:userId,:productId,:shoesSize)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bindParam(':userId', $userId);
    $insertStmt->bindParam(':productId', $productId);
    $insertStmt->bindParam(':shoesSize', $shoesSize);
    $insertResult = $insertStmt->execute();
    if ($insertResult) {
        $message["message"] = "The product was added to the cart";
        echo json_encode($message);
    }
}
