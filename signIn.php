<?php

include "connect.php";

$json = file_get_contents('php://input');
$user = json_decode($json);
$email = $user->email;
$password = $user->password;

$query = "SELECT * FROM user WHERE email=:email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $verifyPassword = password_verify($password, $row["password"]);
    if ($verifyPassword) {
        $result["userId"]=$row['id'];
        $result["email"] = $row['email'];
        $result["message"] = "Welcome";
    } else {
        $result["message"] = "Your password is wrong!";
    }
} else {
    $result["message"] = "Your username is wrong!";
}

echo json_encode($result);
