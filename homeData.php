<?php

include "connect.php";

$json = file_get_contents("php://input");
$userDetails = json_decode($json);
$userId = $userDetails->userId;

homeData($conn, $userId);

function homeData($conn, $userId)
{
    $result = array();
    $result['slider'] = slider($conn);
    $result['newArrivals'] = newArrivals($conn, $userId);
    $result['mostPopular'] = mostPopular($conn, $userId);
    echo json_encode($result);
}

function slider($conn)
{
    $query = "SELECT * FROM slider";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $slider = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $slider[] = $row;
    }
    return $slider;
}

function newArrivals($conn, $userId)
{
    $query = "SELECT * FROM product ORDER BY date_create DESC";
    return extracted($conn, $query, $userId);
}

function mostPopular($conn, $userId)
{
    $query = "SELECT * FROM product ORDER BY visited DESC";
    return extracted($conn, $query, $userId);
}

/**
 * @param $conn
 * @param $query
 * @return array
 */
function extracted($conn, $query, $userId)
{
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $counter = 0;
    $result = array();
    while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) && $counter < 10) {
        $counter++;
        $record = array();
        $record = $row;
        $productQuery = "SELECT * FROM favorits WHERE productId=:productId AND userId=:userId";
        $productStmt = $conn->prepare($productQuery);
        $productStmt->bindParam(":productId", $row['id']);
        $productStmt->bindParam(":userId", $userId);
        $productStmt->execute();
        if ($productStmt->fetch(PDO::FETCH_ASSOC)) {
            $record["favorite"] = 1;
        } else {
            $record["favorite"] = 0;
        }
        $result[] = $record;
    }
    return $result;
}