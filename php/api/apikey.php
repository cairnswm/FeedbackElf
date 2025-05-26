<?php

include_once "../corsheaders.php";
include_once("../gapiv2/dbconn.php");
include_once("../utils.php");

$key = getParam('key', "");

if ($key === "") {
    die(json_encode(['error' => 'API key is missing.']));
}

$sql = "SELECT p.id, p.name, p.image_url, p.enable_reviews, p.enable_bugs, 
        COALESCE(AVG(r.rating), 0) AS rating,
        COUNT(DISTINCT r.id) AS rating_count,
        COUNT(DISTINCT f.id) AS feature_count,
        COUNT(DISTINCT b.id) AS bug_count
    FROM products p
    LEFT JOIN reviews r ON r.product_id = p.id
    LEFT JOIN features f ON f.product_id = p.id
    LEFT JOIN bugs b ON b.product_id = p.id
    WHERE p.api_key = ?
    GROUP BY p.id, p.name, p.image_url, p.enable_reviews, p.enable_bugs
    LIMIT 1";

$row = executeSQL($sql, [$key]);
if (!$row) {
    die(json_encode(['error' => 'Invalid API key.']));
}


echo json_encode($row[0]);