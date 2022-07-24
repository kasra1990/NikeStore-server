<?php
include "connect.php";
//get json
$json = file_get_contents("php://input");
$user = json_decode($json);
$email = $user->email;
$password = $user->password;
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$updateSql = "UPDATE user SET password=:hashPassword WHERE email=:email";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bindParam(':hashPassword', $password_hash);
$updateStmt->bindParam(':email', $email);
$updateResult = $updateStmt->execute();
if ($updateResult) {
    $message["message"] = "Password changed successfully";
    $message["email"] = $email;
    echo json_encode($message);
}else{
    $message["message"] = "Server side error";
    echo json_encode($message);
}


