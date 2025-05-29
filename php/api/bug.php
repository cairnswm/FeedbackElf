<?php

include_once "../corsheaders.php";
include_once("../gapiv2/dbconn.php");
include_once("../utils.php");

$key = getParam('key', null);
$id = getParam('product_id', null);
$username = getParam('user_name', null);
$useremail = getParam('user_email', null);
$severity = getParam('severity', null);
$title = getParam('title', null);
$description = getParam('description', null);

$sql = "insert into bugs (product_id, user_name, user_email, status, severity, title, description)
   values (?, ?, ?, ?, ?, ?, ?)";

$row = executeSQL($sql, [$id, $username, $useremail, "open", $severity, $title, $description]);
if (!$row) {
    die(json_encode(['error' => 'Failed to create bug report.']));
}

$response = [
    'success' => true,
    'message' => 'Bug report created successfully.'
];

echo json_encode($response);