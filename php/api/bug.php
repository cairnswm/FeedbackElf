<?php

include_once("../gapiv2/dbconn.php");
include_once("../utils.php");

$key = getParam('key', null);
$id = getParam('id', null);
$username = getParam('username', null);
$useremail = getParam('useremail', null);
$severity = getParam('severity', null);
$title = getParam('title', null);
$description = getParam('description', null);

if ($key === null) {
    die(json_encode(['error' => 'API key is missing.']));
}

$sql = "insert into bugs (product_id, user_name, user_email, status, severity, title, description) 
        select id, ?, ?, ?, ?, ? from products where api_key = ? limit 1";

$row = executeSQL($sql, [$username, $useremail, "open", $severity, $title, $description, $key]);
if (!$row) {
    die(json_encode(['error' => 'Failed to create bug report.']));
}

$response = [
    'success' => true,
    'message' => 'Bug report created successfully.'
];

echo json_encode($response);