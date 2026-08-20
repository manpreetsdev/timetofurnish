<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoLinkFixer
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (method_exists($response, 'headers') && strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
            $content = $response->getContent();
            
            // The paths that cause 302 redirects for guests
            $routes = [
                '\/dashboard', '\/purchase_history', '\/wishlists', '\/logout',
                '\/admin', '\/auction_product_bids', '\/shops',
                '\/all-notifications', '\/conversations', '\/followed-seller\/store',
                '\/cookies\/accept-all'
            ];
            
            // Match any <a> tag that points to one of these routes
            $pattern = '/<a([^>]*?)href="([^"]*(?:' . implode('|', $routes) . ')[^"]*)"([^>]*?)>/i';
            
            $content = preg_replace_callback($pattern, function($matches) {
                // Remove rel="nofollow" if it was added manually earlier
                $attr1 = preg_replace('/\s*rel="nofollow"/i', '', $matches[1]);
                $attr2 = preg_replace('/\s*rel="nofollow"/i', '', $matches[3]);
                $url = $matches[2];
                
                return '<a' . $attr1 . ' href="javascript:void(0);" onclick="window.location.href=\''.$url.'\'"' . $attr2 . '>';
            }, $content);
            
            $response->setContent($content);
        }

        return $response;
    }
}
