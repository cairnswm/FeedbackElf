<?php

function getProductsforApplication($config, $id)
{

    $query = "SELECT
  p.id,
  p.name,
  p.description,
  p.folder_id,
  p.application_id,
  p.image_url,
  p.api_key,
  p.enable_reviews,
  p.enable_bugs,
  p.enable_features,
  p.created_at,
  p.modified_at,
  
  -- Derived fields
  COALESCE(AVG(r.rating), 0) AS rating,
  COUNT(DISTINCT r.id) AS review_count,
  COUNT(DISTINCT f.id) AS feature_count,
  COUNT(DISTINCT b.id) AS bug_count

FROM products p

LEFT JOIN reviews r ON r.product_id = p.id
LEFT JOIN features f ON f.product_id = p.id
LEFT JOIN bugs b ON b.product_id = p.id

WHERE p.application_id = ?
GROUP BY p.id;
";

    $rows = executeSQL($query, [$config['where']['application_id']]);

    return $rows;
}
function getStandaloneProductsForUser($config, $id)
{
    $query = "SELECT
  p.id,
  p.name,
  p.description,
  p.folder_id,
  p.application_id,
  p.image_url,
  p.api_key,
  p.enable_reviews,
  p.enable_bugs,
  p.enable_features,
  p.created_at,
  p.modified_at,

  -- Derived fields
  COALESCE(AVG(r.rating), 0) AS rating,
  COUNT(DISTINCT r.id) AS review_count,
  COUNT(DISTINCT f.id) AS feature_count,
  COUNT(DISTINCT b.id) AS bug_count

FROM products p

LEFT JOIN reviews r ON r.product_id = p.id
LEFT JOIN features f ON f.product_id = p.id
LEFT JOIN bugs b ON b.product_id = p.id

WHERE p.user_id = ? and p.application_id IS NULL
GROUP BY p.id;
";

    $rows = executeSQL($query, [$config['where']['user_id']]);

    return $rows;
}