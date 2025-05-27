<?php

include_once "../corsheaders.php";
include_once("../gapiv2/dbconn.php");
include_once("../utils.php");

$key = getParam('key', null);
$id = getParam('product_id', null);
$username = getParam('user_name', null);
$useremail = getParam('user_email', null);
$title = getParam('title', null);
$description = getParam('description', null);
$priority = getParam('priority', null);


$sql = "insert into features (product_id, user_name, user_email, title, description, status, priority)
values (?, ?, ?, ?, ?, ?, ?)";

$row = executeSQL($sql, [$id, $username, $useremail, $title, $description, "pending", $priority]);
if (!$row) {
    die(json_encode(['error' => 'Failed to create feature request.']));
}

$response = [
    'success' => true,
    'message' => 'Feature request created successfully.'
];

echo json_encode($response);