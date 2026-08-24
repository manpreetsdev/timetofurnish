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
            
            // Match any <a> tag that points to one of these routes, handling both single and double quotes
            $pattern = '/<a([^>]*?)href=(["\'])([^"\']*(?:' . implode('|', $routes) . ')[^"\']*)\2([^>]*?)>/i';
            
            if (!auth()->check()) {
                $loginUrl = route('user.login');

                $content = preg_replace_callback($pattern, function($matches) use ($loginUrl) {
                    $attr1 = preg_replace('/\s*rel=(["\']).*?\1/i', '', $matches[1]);
                    $attr2 = preg_replace('/\s*rel=(["\']).*?\1/i', '', $matches[4]);

                    return '<a' . $attr1 . ' href="' . e($loginUrl) . ' ' . $attr2 . '>';
                }, $content);
            }
            
            $response->setContent($content);
        }

        return $response;
    }
}