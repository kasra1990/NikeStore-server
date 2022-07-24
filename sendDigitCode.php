<?php
include "connect.php";
$json = file_get_contents('php://input');
$user = json_decode($json);
$email = $user->email;

$sql = "SELECT * FROM user WHERE email=:email";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    try {
        $recoveryCode = random_int(1000, 9999);
    } catch (Exception $e) {
    }
    $checkQuery = "SELECT * FROM digitCode WHERE userId=:userId";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':userId', $row["id"]);
    $checkStmt->execute();
    $checkRow = $checkStmt->fetch(PDO::FETCH_ASSOC);
    $password_hash = password_hash($recoveryCode, PASSWORD_DEFAULT);
    var_dump($password_hash);
    if($checkRow){
        $updateSql = "UPDATE digitCode SET digitCode=:digitCode WHERE userId=:userId";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':digitCode', $password_hash);
        $updateStmt->bindParam(':userId', $checkRow["userId"]);
        $updateResult = $updateStmt->execute();
        if ($updateResult) {
            sendEmail($email,$recoveryCode,$row["id"]);
        }
    }else{
        $insertSql = "INSERT INTO digitCode (userId,digitCode) VALUE (:userId,:digitCode)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bindParam(':userId', $row["id"]);
        $insertStmt->bindParam(':digitCode', $password_hash);
        $insertResult = $insertStmt->execute();
        if ($insertResult) {
            sendEmail($email,$recoveryCode,$row["id"]);
        }
    }



} else {
    $result["message"] = "You are not registered";
    $result["id"] = 0;
    echo json_encode($result);
}

function sendEmail($email,$recoveryCode,$id){
    $subject = 'Send password';
    $message = 'Hi' . "\r\n" . 'Your password: ' . $recoveryCode;
    $headers = 'From: kasra@example.com';
    $send = mail($email, $subject, $message, $headers);
    if ($send) {
        $result["message"] = "Email has been sent";
        $result["id"] = $id;
        echo json_encode($result);
    }
}




