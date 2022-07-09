<?php

include "connect.php";

$json = file_get_contents("php://input");
$userDetails = json_decode($json);
$userId = $userDetails->userId;
$productId = $userDetails->productId;

if ($userId != "") {
    updateFavoriteTable($conn, $userId, $productId);
}else{
    $message["message"] = "Please log in first";
    echo json_encode($message);
}

function updateFavoriteTable($conn, $userId, $productId)
{
    $sql = "SELECT * FROM favorits WHERE userId=:userId AND productId=:productId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $updateSql = "DELETE FROM favorits WHERE userId=:userId AND productId=:productId";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':userId', $userId);
        $updateStmt->bindParam(':productId', $productId);
        $updateResult = $updateStmt->execute();
        if ($updateResult) {
            $message["message"] = "delete form favorite";
            echo json_encode($message);
        }
    } else {
        $insertSql = "INSERT INTO favorits (userId,productId) VALUE (:userId,:productId)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bindParam(':userId', $userId);
        $insertStmt->bindParam(':productId', $productId);
        $insertResult = $insertStmt->execute();
        if ($insertResult) {
            $message["message"] = "add to favorite";
            echo json_encode($message);
        }
    }
}
