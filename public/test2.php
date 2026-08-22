<?php
$content = '<a href="https://timetofurnish.com/dashboard" class="user-top-menu-name has-transition ml-3">Dashboard</a>';
$routes = [
    '\/dashboard', '\/purchase_history', '\/wishlists', '\/logout',
    '\/admin', '\/auction_product_bids', '\/shops',
    '\/all-notifications', '\/conversations', '\/followed-seller\/store',
    '\/cookies\/accept-all'
];
$pattern = '/<a([^>]*?)href=(["\'])([^"\']*(?:' . implode('|', $routes) . ')[^"\']*)\2([^>]*?)>/i';

$content = preg_replace_callback($pattern, function($matches) {
    $attr1 = preg_replace('/\s*rel="nofollow"/i', '', $matches[1]);
    $attr2 = preg_replace('/\s*rel="nofollow"/i', '', $matches[4]);
    $url = $matches[3];
    $encodedUrl = base64_encode($url);
    
    return '<a' . $attr1 . ' href="javascript:void(0);" onclick="window.location.href=atob(\''.$encodedUrl.'\')"' . $attr2 . '>';
}, $content);

echo $content . "\n";
