<?php
include "connect.php";
$json = file_get_contents('php://input');
$user = json_decode($json);
$userId = $user->id;
$passRecovery = $user->verifyCode;

$sql = "SELECT * FROM digitCode WHERE userId=:userId";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userId', $userId);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $verifyCode = password_verify($passRecovery, $row["digitCode"]);
    if ($verifyCode) {
        $sqlEmail = "SELECT * FROM user WHERE id=:userId";
        $stmtEmail = $conn->prepare($sqlEmail);
        $stmtEmail->bindParam(':userId', $userId);
        $stmtEmail->execute();
        $user = $stmtEmail->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $queryDelete = "DELETE FROM digitCode WHERE userId=:userId";
            $stmtDelete = $conn->prepare($queryDelete);
            $stmtDelete->bindParam(':userId', $userId);
            $stmtDelete->execute();
            $result["message"] = "OK";
            $result["email"] = $user["email"];
            echo json_encode($result);
        }

    } else {
        $result["message"] = "The entered code is wrong";
        echo json_encode($result);
    }

}
