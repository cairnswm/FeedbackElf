<?php

include_once dirname(__FILE__) . "/../corsheaders.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once dirname(__FILE__) . "/../gapiv2/dbconn.php";
include_once dirname(__FILE__) . "/../gapiv2/v2apicore.php";
include_once dirname(__FILE__) . "/../utils.php";
include_once dirname(__FILE__) . "/../security/security.config.php";

// Get authentication details
$appid = getAppId();
$token = getToken();

// if (validateJwt($token, false) == false) {
//     http_response_code(401);
//     echo json_encode([
//         'error' => true,
//         'message' => 'Unauthorized'
//     ]);
//     die();
// }

// $user = getUserFromToken($token);
// $userid = $user->id;

$userid = 100;

include_once dirname(__FILE__) . "/functions/applications.php";
include_once dirname(__FILE__) . "/functions/products.php";


// Define the configurations
$feedbackconfig = [
    "user" => [
        "tablename" => "products",
        "key" => "user_id",
        "select" => ["id", "user_id", "name"],
        "create" => false,
        "update" => [],
        "delete" => false,
        "subkeys" => [
            "applications" => [
                "tablename" => "applications",
                "key" => "user_id",
                "select" => "getApplicationsForUser",
                "beforeselect" => "",
                "beforecreate" => "",
                "beforeupdate" => "",
                "beforedelete" => "",
            ],
            "products" => [
                "tablename" => "products",
                "key" => "user_id",
                "select" => ["id", "user_id", "name", "description", "image_url", "application_id", "folder_id", "api_key", "enable_bugs", "enable_reviews", "enable_features"],
            ],
            "items" => [
                "tablename" => "products",
                "key" => "user_id",
                "select" => "getUserItems",
            ],
            "standalone" => [
                "tablename" => "products",
                "key" => "user_id",
                "select" => "getStandaloneProductsForUser",
            ],
        ]
    ],
    "application" => [
        "tablename" => "applications",
        "key" => "id",
        "select" => ["id", "user_id", "name", "description", "image_url"],
        "create" => ["user_id", "name", "description", "image_url"],
        "update" => ["user_id", "name", "description", "image_url"],
        "delete" => true,
        "beforeselect" => "",
        "beforecreate" => "beforeCreateItem",
        "beforeupdate" => "",
        "beforedelete" => "",
        "aftercreate" => "afterCreateProduct",
        "subkeys" => [
            "products" => [
                "tablename" => "products",
                "key" => "application_id",
                "select" => "getProductsforApplication",
            ],
            "folders" => [
                "tablename" => "folders",
                "key" => "application_id",
                "select" => ["id", "user_id", "name", "image_url", "parent_id", "application_id"],
            ],
        ]
    ],

    "product" => [
        "tablename" => "products",
        "key" => "id",
        "select" => ["id", "user_id", "name", "description", "image_url", "application_id", "folder_id", "api_key", "enable_bugs", "enable_reviews", "enable_features"],
        "create" => ["user_id", "name", "description", "image_url", "application_id", "folder_id", "api_key", "enable_bugs", "enable_reviews", "enable_features"],
        "update" => ["user_id", "name", "description", "image_url", "application_id", "folder_id", "api_key", "enable_bugs", "enable_reviews", "enable_features"],
        "delete" => true,
        "beforeselect" => "",
        "beforecreate" => "beforeCreateItem",
        "beforeupdate" => "",
        "beforedelete" => "",
        "aftercreate" => "afterCreateProduct",
        "subkeys" => [
            "bugs" => [
                "tablename" => "bugs",
                "key" => "product_id",
                "select" => ["id", "product_id", "title", "description", "severity", "status", "user_name", "user_email", "created_at", "modified_at"],
                "beforeselect" => ""
            ],
            "reviews" => [
                "tablename" => "reviews",
                "key" => "product_id",
                "select" => ["id", "product_id", "rating", "rating", "content", "user_name", "user_email", "created_at", "modified_at"],
                "beforeselect" => ""
            ],
            "features" => [
                "tablename" => "features",
                "key" => "product_id",
                "select" => ["id", "product_id", "title", "description", "status", "user_name", "user_email", "created_at", "modified_at"],
                "beforeselect" => ""
            ],
        ]
    ],
    "bug" => [
        "tablename" => "bugs",
        "key" => "id",
        "select" => ["id", "product_id", "title", "description", "severity", "status", "user_name", "user_email", "created_at", "modified_at"],
        "create" => ["product_id", "title", "description", "severity", "status", "user_name", "user_email"],
        "update" => ["product_id", "title", "description", "severity", "status", "user_name", "user_email"],
        "subkeys" => [
            "notes" => [
                "tablename" => "bug_notes",
                "key" => "bug_id",
                "select" => ["id", "bug_id", "user_id", "content"],
            ]
        ]
    ],
    "review" => [
        "tablename" => "reviews",
        "key" => "id",
        "select" => ["id", "product_id", "rating", "content", "user_name", "user_email", "created_at", "modified_at"],
        "create" => ["product_id", "rating", "content", "user_name", "user_email"],
        "update" => ["product_id", "rating", "content", "user_name", "user_email"],
        "subkeys" => [
            "notes" => [
                "tablename" => "review_notes",
                "key" => "review_id",
                "select" => ["id", "review_id", "user_id", "content"],
            ]
        ]
    ],
    "feature" => [
        "tablename" => "features",
        "key" => "id",
        "select" => ["id", "product_id", "title", "description", "status", "user_name", "user_email", "created_at", "modified_at"],
        "create" => ["product_id", "title", "description", "status", "user_name", "user_email"],
        "update" => ["product_id", "title", "description", "status", "user_name", "user_email"],
        "subkeys" => [
            "notes" => [
                "tablename" => "feature_notes",
                "key" => "feature_id",
                "select" => ["id", "feature_id", "user_id", "content"],
            ]
        ]
    ],
    "apikey" => [
        "tablename" => "products",
        "key" => "api_key",
        "select" => ["id", "user_id", "name", "description", "image_url", "application_id", "folder_id", "api_key", "enable_bugs", "enable_reviews", "enable_features"]
    ],
    "bug_note" => [
        "tablename" => "bug_notes",
        "key" => "id",
        "select" => ["id", "bug_id", "user_id", "content"],
        "create" => ["bug_id", "user_id", "content"],
        "update" => ["bug_id", "user_id", "content"],
        "delete" => true,
    ],

    "review_note" => [
        "tablename" => "review_notes",
        "key" => "id",
        "select" => ["id", "review_id", "user_id", "content"],
        "create" => ["review_id", "user_id", "content"],
        "update" => ["review_id", "user_id", "content"],
        "delete" => true,
    ],

    "feature_note" => [
        "tablename" => "feature_notes",
        "key" => "id",
        "select" => ["id", "feature_id", "user_id", "content"],
        "create" => ["feature_id", "user_id", "content"],
        "update" => ["feature_id", "user_id", "content"],
        "delete" => true,
    ],

    "folders" => [
        "tablename" => "folders",
        "key" => "id",
        "select" => ["id", "user_id", "name", "image_url", "parent_id", "application_id"],
        "create" => ["user_id", "name", "image_url", "parent_id", "application_id"],
        "update" => ["user_id", "name", "image_url", "parent_id", "application_id"],
        "delete" => true,
        "beforeselect" => "",
        "beforecreate" => "",
        "beforeupdate" => "",
        "beforedelete" => "",
        "subkeys" => [
            "products" => [
                "tablename" => "products",
                "key" => "folder_id",
                "select" => ["id", "user_id", "name", "description", "image_url", "application_id", "folder_id", "api_key", "enable_bugs", "enable_reviews", "enable_features"],
            ]
        ]
    ],

    "post" => [
        "createApplication" => "insertApplication",
        "decodeApiKey" => "reverseApiKey",
        "deleteApplication" => "deleteApplication",
        "insertLink" => "insertLink",

    ]
];

runAPI($feedbackconfig);

function reverseApiKey($fields)
{
    $id = $fields['apikey'];
    return decodeApiKey($id);
}

function insertApplication($fields)
{
    global $gapiconn, $userid;

    $query = "INSERT INTO applications (user_id, name, description) VALUES (?, ?, ?)";
    $stmt = $gapiconn->prepare($query);
    $stmt->bind_param("iss", $userid, $fields['name'], $fields['description']);
    $stmt->execute();

    $appId = $stmt->insert_id;
    $stmt->close();

    $apiKey = createApiKey($appId);

    $updateQuery = "UPDATE applications SET api_key = ? WHERE id = ?";
    $stmt = $gapiconn->prepare($updateQuery);
    $stmt->bind_param("si", $apiKey, $appId);
    $stmt->execute();
    $stmt->close();

    return [
        'id' => $appId,
        'api_key' => $apiKey
    ];
}

function createApiKey($id)
{
    // Generate a full UUID (random 16 bytes)
    $data = random_bytes(16);
    $uuid = vsprintf('%08s-%04s-%04x-%04x-%012x', [
        bin2hex(substr($data, 0, 4)),           // first 8 chars
        bin2hex(substr($data, 4, 2)),           // 4 chars
        (hexdec(bin2hex(substr($data, 6, 2))) & 0x0fff) | 0x4000, // version 4
        (hexdec(bin2hex(substr($data, 8, 2))) & 0x3fff) | 0x8000, // variant
        hexdec(bin2hex(substr($data, 10, 6)))   // 12 chars
    ]);

    // Extract and transform
    $parts = explode('-', $uuid);
    $base = $parts[0];                     // First 8 characters
    $baseInt = hexdec($base);
    $encodedInt = $baseInt + $id;
    $encoded = strtolower(str_pad(dechex($encodedInt), 8, '0', STR_PAD_LEFT));

    // Replace second part of UUID with encoded value
    $parts[1] = $encoded;
    return implode('-', $parts);
}

function decodeApiKey($guid)
{
    $parts = explode('-', $guid);
    if (count($parts) < 2) {
        throw new Exception("Invalid GUID format.");
    }

    $base = $parts[0];
    $encoded = $parts[1];

    $baseInt = hexdec($base);
    $encodedInt = hexdec($encoded);

    return $encodedInt - $baseInt;
}

function deleteApplication($fields)
{
    global $gapiconn;

    $appId = $fields['id'];

    // Delete from invitations
    $query = "DELETE FROM invitations WHERE application_id = ?";
    $stmt = $gapiconn->prepare($query);
    $stmt->bind_param("i", $appId);
    $stmt->execute();
    $stmt->close();

    // Delete from application_users
    $query = "DELETE FROM application_users WHERE application_id = ?";
    $stmt = $gapiconn->prepare($query);
    $stmt->bind_param("i", $appId);
    $stmt->execute();
    $stmt->close();

    // Delete from events
    $query = "DELETE FROM events WHERE application_id = ?";
    $stmt = $gapiconn->prepare($query);
    $stmt->bind_param("i", $appId);
    $stmt->execute();
    $stmt->close();

    // Delete from analytics
    $query = "DELETE FROM analytics WHERE application_id = ?";
    $stmt = $gapiconn->prepare($query);
    $stmt->bind_param("i", $appId);
    $stmt->execute();
    $stmt->close();

    // Finally, delete from applications
    $query = "DELETE FROM applications WHERE id = ?";
    $stmt = $gapiconn->prepare($query);
    $stmt->bind_param("i", $appId);
    $stmt->execute();
    $stmt->close();

    return [
        'id' => $appId,
        'deleted' => true
    ];
}





function getUserItems($config)
{

    global $gapiconn;

    // var_dump($config);

    $id = $config["where"]["user_id"] ?? null;

    $sql = "SELECT CONCAT('F-',id) id, 'folder' type, NAME name, description, image, folder_id FROM folder WHERE user_id = ?
UNION
SELECT CONCAT('P-',id) id, 'product' type, NAME name, description, image, folder_id FROM product WHERE user_id = ?";

    $stmt = $gapiconn->prepare($sql);
    $stmt->bind_param("ii", $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();

    return $rows;
}

function updateProductReviews($config, $fields, $new_record)
{
    global $gapiconn;

    $productId = $fields['product_id'];
    $userId = $fields['user_id'];

    // Calculate the average rating
    $query = "SELECT count(1) as count_review, AVG(rating) as avg_rating FROM product_reviews WHERE product_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($gapiconn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $productId, $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $countRating = round($row['count_review'], 2);
    $avgRating = round($row['avg_rating'], 2);
    mysqli_stmt_close($stmt);

    // Get the number of bugs that are in status 'open' or 'in-progress'
    $bugQuery = "SELECT COUNT(*) as bug_count FROM product_bugs WHERE product_id = ? AND user_id = ? AND status IN ('open', 'in-progress')";
    $stmt = mysqli_prepare($gapiconn, $bugQuery);
    mysqli_stmt_bind_param($stmt, "ii", $productId, $userId);
    mysqli_stmt_execute($stmt);
    $bugResult = mysqli_stmt_get_result($stmt);
    $bugRow = mysqli_fetch_assoc($bugResult);
    $bugCount = $bugRow['bug_count'];
    mysqli_stmt_close($stmt);

    // Get the number of features that are in status 'planned', 'in-progress', or 'pending'
    $featureQuery = "SELECT COUNT(*) as feature_count FROM product_features WHERE product_id = ? AND user_id = ? AND status IN ('planned', 'in-progress', 'pending')";
    $stmt = mysqli_prepare($gapiconn, $featureQuery);
    mysqli_stmt_bind_param($stmt, "ii", $productId, $userId);
    mysqli_stmt_execute($stmt);
    $featureResult = mysqli_stmt_get_result($stmt);
    $featureRow = mysqli_fetch_assoc($featureResult);
    $featureCount = $featureRow['feature_count'];
    mysqli_stmt_close($stmt);

    // Update the product with the new average rating
    $updateQuery = "UPDATE products SET rating_count = ?, review_average = ?, features_count = ?, bugs_count = ? WHERE id = ?";
    $stmt = mysqli_prepare($gapiconn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ddiii", $countRating, $avgRating, $featureCount, $bugCount, $productId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return true;
}

function beforeCreateItem($config, $fields)
{
    global $userid;

    if (isset($fields['user_id'])) {
        $fields['user_id'] = $userid;
    } else {
        $fields['user_id'] = $userid;
    }

    return [$config, $fields];
}

function afterCreateProduct($config, $data, $new_record)
{
    global $gapiconn, $userid;
    $new_record = $new_record[0];

    $apikey = createApiKey($new_record['id']);

    $new_record['api_key'] = $apikey;
    $query = "UPDATE products SET api_key = ? WHERE id = ?";
    $stmt = $gapiconn->prepare($query);
    $stmt->bind_param("si", $new_record['api_key'], $new_record['id']);
    $stmt->execute();
    $stmt->close();

    return [$config, $data, $new_record];
}
