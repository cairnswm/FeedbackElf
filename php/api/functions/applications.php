<?php

function getApplicationsForUser($config, $id)
{

    $query = "SELECT 
  a.id,
  a.name,
  a.description,
  a.developer,
  a.category,
  a.image_url,
  a.created_at,
  a.modified_at,
  
  -- Derived fields
  AVG(r.rating) AS rating,
  COUNT(DISTINCT r.id) AS rating_count,
  COUNT(DISTINCT f.id) AS feature_count,
  COUNT(DISTINCT b.id) AS bug_count

FROM applications a

LEFT JOIN products p ON p.application_id = a.id
LEFT JOIN reviews r ON r.product_id = p.id
LEFT JOIN features f ON f.product_id = p.id
LEFT JOIN bugs b ON b.product_id = p.id

WHERE a.user_id = ?
GROUP BY a.id;
";

    $rows = executeSQL($query, [$config['where']['user_id']]);

    return $rows;
}
