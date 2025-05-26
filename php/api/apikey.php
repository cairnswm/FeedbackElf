<?php

include_once "../corsheaders.php";
include_once("../gapiv2/dbconn.php");
include_once("../utils.php");

$key = getParam('key', "");

if ($key === "") {
    die(json_encode(['error' => 'API key is missing.']));
}

$sql = "SELECT id, name, image_url, enable_reviews, enable_bugs, enable_reviews FROM products WHERE api_key = ? LIMIT 1";

$row = executeSQL($sql, [$key]);
if (!$row) {
    die(json_encode(['error' => 'Invalid API key.']));
}


echo json_encode($row[0]);