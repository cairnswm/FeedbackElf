<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../corsheaders.php";
include_once("../gapiv2/dbconn.php");
include_once("../utils.php");

$id = getParam('product_id', "");
$username = getParam('user_name', "");
$useremail = getParam('user_email', "");
$title = getParam('title',  "");
$content = getParam('content', "");
$rating = getParam('rating', "");

$sql = "insert into reviews (product_id, user_name, user_email, rating, title, content) 
        values (?, ?, ?, ?, ?, ?)";

$row = executeSQL($sql, [$id, $username, $useremail, $rating, $title, $content], []);
if (!$row) {
    die(json_encode(['error' => 'Failed to create review.']));
}

$response = [
    'success' => true,
    'message' => 'Review created successfully.'
];

echo json_encode($response);