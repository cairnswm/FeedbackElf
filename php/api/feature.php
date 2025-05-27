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
$priority = getParam('priority', null);


$sql = "insert into features (product_id, user_name, user_email, title, description, status, priority) 
        select id, ?, ?, ?, ?, ?, ? from products where api_key = ? limit 1";

$row = executeSQL($sql, [$username, $useremail, $title, $description, "pending", $priority, $key]);
if (!$row) {
    die(json_encode(['error' => 'Failed to create feature request.']));
}

$response = [
    'success' => true,
    'message' => 'Feature request created successfully.'
];

echo json_encode($response);