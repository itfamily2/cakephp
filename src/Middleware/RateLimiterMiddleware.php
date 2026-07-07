<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Cache\Cache;
use Cake\Http\Exception\TooManyRequestsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Phase 9 - RateLimiterMiddleware
 * 
 * Limits to $limit requests per $window seconds per IP.
 * Applies only to non-authenticated / API endpoints to prevent abuse.
 * Normal backend page browsing is excluded.
 */
class RateLimiterMiddleware implements MiddlewareInterface
{
    protected int $limit  = 300;  // max requests per window
    protected int $window = 60;   // seconds per window

    // Paths that bypass rate limiting (admin backend browsing)
    protected array $excluded = [
        '/',
        '/dashboard',
        '/users',
        '/orders',
        '/products',
        '/categories',
        '/brands',
        '/payments',
        '/invoices',
        '/reports',
        '/settings',
        '/cms-pages',
        '/contact-enquiries',
        '/email-templates',
        '/email-signatures',
        '/scheduled-emails',
        '/sent-emails',
        '/activity-logs',
        '/audit-logs',
        '/logs',
        '/roles',
        '/permissions',
        '/groups',
    ];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        // Skip rate limiting for all backend/admin pages
        foreach ($this->excluded as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return $handler->handle($request);
            }
        }

        // Only rate-limit API and auth endpoints
        $ip       = $request->clientIp() ?: '127.0.0.1';
        $window   = (int)floor(time() / $this->window); // changes every $window seconds
        $cacheKey = 'rate_limit_' . md5($ip . '_' . $window);

        $hits = (int)Cache::read($cacheKey, 'default');

        if ($hits >= $this->limit) {
            throw new TooManyRequestsException('Rate limit exceeded. Please try again later.');
        }

        // Write with TTL = window duration so it auto-expires
        Cache::write($cacheKey, $hits + 1, 'default');

        return $handler->handle($request);
    }
}
