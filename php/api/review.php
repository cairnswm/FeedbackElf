<?php

include_once "../corsheaders.php";
include_once("../gapiv2/dbconn.php");
include_once("../utils.php");

$key = getParam('key', null);
$id = getParam('id', null);
$username = getParam('username', null);
$useremail = getParam('useremail', null);
$title = getParam('title', null);
$description = getParam('description', null);
$rating = getParam('rating', null);

if ($key === null) {
    die(json_encode(['error' => 'API key is missing.']));
}

$sql = "insert into reviews (product_id, user_name, user_email, rating, title, content) 
        select id, ?, ?, ?, ?, ? from products where api_key = ? limit 1";

$row = executeSQL($sql, [$username, $useremail, $rating, $title, $description, $key]);
if (!$row) {
    die(json_encode(['error' => 'Failed to create review.']));
}

$response = [
    'success' => true,
    'message' => 'Review created successfully.'
];

echo json_encode($response);