<?php
$servername = "localhost";
$username = "expertde_nikeStore";
$password = "nikeStore2080198785";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$username", $username, $password);
    $conn->exec("SET character_set_connection='utf8'");
    $conn->exec("SET NAMES 'utf8'");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "ok";
} catch (PDOException $e) {
//    echo "connection failed: " . $e->getMessage();
}