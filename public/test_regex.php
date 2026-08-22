<?php
$routes = [
    "\/dashboard", "\/purchase_history", "\/wishlists", "\/logout",
    "\/admin", "\/auction_product_bids", "\/shops",
    "\/all-notifications", "\/conversations", "\/followed-seller\/store",
    "\/cookies\/accept-all"
];
$pattern = '/<a([^>]*?)href=(["\'])([^"\']*(?:' . implode('|', $routes) . ')[^"\']*)\2([^>]*?)>/i';
$html = file_get_contents("scratch_home.html");
preg_match_all($pattern, $html, $matches);
print_r($matches[3]);
