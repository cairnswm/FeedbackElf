<?php

include_once "../corsheaders.php";
include_once("../gapiv2/dbconn.php");
include_once("../utils.php");

$key = getParam('key', null);
$id = getParam('product_id', null);
$username = getParam('user_name', null);
$useremail = getParam('user_email', null);
$title = getParam('title', default: null);
$content = getParam('content', null);
$rating = getParam('rating', null);

$sql = "insert into reviews (product_id, user_name, user_email, rating, title, content) 
        values (?, ?, ?, ?, ?, ?)";

echo $sql . "\n";
var_dump([$id, $username, $useremail, $rating, $title, $content]);

$row = executeSQL($sql, [$id, $username, $useremail, $rating, $title, $content]);
if (!$row) {
    die(json_encode(['error' => 'Failed to create review.']));
}



$response = [
    'success' => true,
    'message' => 'Review created successfully.'
];

echo json_encode($response);