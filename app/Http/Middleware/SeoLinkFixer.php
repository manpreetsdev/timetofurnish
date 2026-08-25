<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoLinkFixer
{
    public function handle(Request $request, Closure $next)
    {
        $guestOnlyPages = [
            '/dashboard' => [
                'title' => 'Customer Dashboard Login',
                'description' => 'Sign in to access your TimetoFurnish customer dashboard and account activity.',
                'heading' => 'Login Required',
                'body' => 'Please sign in to view your dashboard, orders, saved items, and account updates.',
            ],
            '/purchase_history' => [
                'title' => 'Order History Login',
                'description' => 'Sign in to access your TimetoFurnish order and purchase history.',
                'heading' => 'Login Required',
                'body' => 'Please sign in to view your purchase history and order details.',
            ],
            '/logout' => [
                'title' => 'Account Access',
                'description' => 'Sign in to manage your TimetoFurnish account session.',
                'heading' => 'You Are Not Logged In',
                'body' => 'There is no active account session to log out from. Sign in to continue.',
            ],
            '/all-notifications' => [
                'title' => 'Notifications Login',
                'description' => 'Sign in to view your TimetoFurnish account notifications.',
                'heading' => 'Login Required',
                'body' => 'Please sign in to view your notifications and account alerts.',
            ],
            '/wishlists' => [
                'title' => 'Wishlist Login',
                'description' => 'Sign in to access your TimetoFurnish wishlist.',
                'heading' => 'Login Required',
                'body' => 'Please sign in to view and manage your wishlist.',
            ],
            '/auction_product_bids' => [
                'title' => 'Auction Bids Login',
                'description' => 'Sign in to access your TimetoFurnish auction bids.',
                'heading' => 'Login Required',
                'body' => 'Please sign in to manage your auction bids and activity.',
            ],
            '/conversations' => [
                'title' => 'Messages Login',
                'description' => 'Sign in to access your TimetoFurnish conversations and messages.',
                'heading' => 'Login Required',
                'body' => 'Please sign in to view your conversations and messages.',
            ],
            '/shops' => [
                'title' => 'Seller Dashboard Login',
                'description' => 'Sign in to access your TimetoFurnish seller shop dashboard.',
                'heading' => 'Seller Login Required',
                'body' => 'Please sign in to manage your shop, products, and seller account.',
            ],
        ];

        if (!auth()->check() && ($request->isMethod('GET') || $request->isMethod('HEAD'))) {
            $path = '/' . ltrim($request->path(), '/');

            if (array_key_exists($path, $guestOnlyPages)) {
                return response()->view('frontend.seo.guest-gate', [
                    'metaTitle' => $guestOnlyPages[$path]['title'],
                    'metaDescription' => $guestOnlyPages[$path]['description'],
                    'heading' => $guestOnlyPages[$path]['heading'],
                    'body' => $guestOnlyPages[$path]['body'],
                ], 200);
            }
        }

        $response = $next($request);

        $contentType = method_exists($response, 'headers') ? (string) $response->headers->get('Content-Type') : '';

        if (method_exists($response, 'getContent') && Str::contains($contentType, 'text/html')) {
            $content = $response->getContent();

            if (!auth()->check()) {
                $loginUrl = route('user.login');
                $guardedPrefixes = [
                    '/dashboard',
                    '/purchase_history',
                    '/wishlists',
                    '/logout',
                    '/admin',
                    '/auction_product_bids',
                    '/all-notifications',
                    '/conversations',
                    '/followed-seller/store',
                    '/cookies/accept-all',
                ];

                $content = preg_replace_callback('/<a\b([^>]*?)href=(["\'])([^"\']+)\2([^>]*)>/i', function ($matches) use ($loginUrl, $guardedPrefixes) {
                    $href = html_entity_decode($matches[3], ENT_QUOTES, 'UTF-8');
                    $parsedPath = parse_url($href, PHP_URL_PATH);

                    if (!$parsedPath) {
                        return $matches[0];
                    }

                    $shouldRewrite = false;
                    foreach ($guardedPrefixes as $prefix) {
                        if ($parsedPath === $prefix || Str::startsWith($parsedPath, $prefix . '/')) {
                            $shouldRewrite = true;
                            break;
                        }
                    }

                    if (!$shouldRewrite && $parsedPath === '/shops') {
                        $shouldRewrite = true;
                    }

                    if (!$shouldRewrite) {
                        return $matches[0];
                    }

                    $attr1 = preg_replace('/\s*rel=(["\']).*?\1/i', '', $matches[1]);
                    $attr2 = preg_replace('/\s*rel=(["\']).*?\1/i', '', $matches[4]);

                    return '<a' . $attr1 . ' href="' . e($loginUrl) . '"' . $attr2 . '>';
                }, $content);
            }

            $requestHost = $request->getHost();
            $content = preg_replace_callback('/<a\b[^>]*href=(["\'])([^"\']+)\1[^>]*>(.*?)<\/a>/is', function ($matches) use ($requestHost) {
                $href = html_entity_decode($matches[2], ENT_QUOTES, 'UTF-8');

                if (!$this->isInternalCdnCgiHref($href, $requestHost)) {
                    return $matches[0];
                }

                return $matches[3];
            }, $content);

            $content = preg_replace_callback('/<a\b([^>]*?)href=(["\']?)([^"\'>\s]+)\2([^>]*)>(.*?)<\/a>/is', function ($matches) use ($requestHost) {
                $href = html_entity_decode($matches[3], ENT_QUOTES, 'UTF-8');

                if (!$this->isInternalCdnCgiHref($href, $requestHost)) {
                    return $matches[0];
                }

                return $matches[5];
            }, $content);

            $content = preg_replace_callback('/<a\b([^>]*?)href=(["\'])([^"\']+)\2([^>]*)>/i', function ($matches) use ($requestHost) {
                $href = html_entity_decode($matches[3], ENT_QUOTES, 'UTF-8');

                if (!$this->isInternalHref($href, $requestHost)) {
                    return $matches[0];
                }

                $attr1 = preg_replace('/\s*rel=(["\']).*?\1/i', '', $matches[1]);
                $attr2 = preg_replace('/\s*rel=(["\']).*?\1/i', '', $matches[4]);

                return '<a' . $attr1 . ' href=' . $matches[2] . $matches[3] . $matches[2] . $attr2 . '>';
            }, $content);

            $content = preg_replace_callback('/<a\b([^>]*?)href=(["\']?)([^"\'>\s]+)\2([^>]*)>/i', function ($matches) use ($requestHost) {
                $href = html_entity_decode($matches[3], ENT_QUOTES, 'UTF-8');

                if (!$this->isInternalHref($href, $requestHost)) {
                    return $matches[0];
                }

                $cleanStart = $this->removeInternalRelAttributes($matches[1]);
                $cleanEnd = $this->removeInternalRelAttributes($matches[4]);
                $quote = $matches[2] !== '' ? $matches[2] : '"';

                return '<a' . $cleanStart . ' href=' . $quote . $matches[3] . $quote . $cleanEnd . '>';
            }, $content);

            $response->setContent($content);
            $this->appendNoTransformDirective($response);
        }

        return $response;
    }

    private function removeInternalRelAttributes(string $attributes): string
    {
        return preg_replace_callback('/\s*rel=(["\'])(.*?)\1/i', function ($matches) {
            $tokens = preg_split('/\s+/', trim($matches[2])) ?: [];
            $tokens = array_values(array_filter($tokens, function ($token) {
                return !in_array(Str::lower($token), ['nofollow', 'ugc', 'sponsored'], true);
            }));

            if (empty($tokens)) {
                return '';
            }

            return ' rel=' . $matches[1] . implode(' ', $tokens) . $matches[1];
        }, $attributes);
    }

    private function appendNoTransformDirective($response): void
    {
        if (!method_exists($response, 'headers')) {
            return;
        }

        $cacheControl = (string) $response->headers->get('Cache-Control', '');
        $directives = array_filter(array_map('trim', explode(',', $cacheControl)));

        if (!in_array('no-transform', $directives, true)) {
            $directives[] = 'no-transform';
        }

        $response->headers->set('Cache-Control', implode(', ', $directives));
    }

    private function isInternalCdnCgiHref(string $href, string $requestHost): bool
    {
        $parsedHost = parse_url($href, PHP_URL_HOST);
        $parsedPath = parse_url($href, PHP_URL_PATH);

        if ($parsedPath === null || !Str::startsWith($parsedPath, '/cdn-cgi/')) {
            return false;
        }

        if ($parsedHost === null) {
            return true;
        }

        return strcasecmp($parsedHost, $requestHost) === 0;
    }

    private function isInternalHref(string $href, string $requestHost): bool
    {
        if ($href === '' || Str::startsWith($href, ['#', 'mailto:', 'tel:', 'javascript:'])) {
            return false;
        }

        $parsedHost = parse_url($href, PHP_URL_HOST);
        $parsedPath = parse_url($href, PHP_URL_PATH);

        if ($parsedHost !== null) {
            return strcasecmp($parsedHost, $requestHost) === 0;
        }

        return $parsedPath !== null && Str::startsWith($parsedPath, '/');
    }
}
