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
 */
class RateLimiterMiddleware implements MiddlewareInterface
{
    protected int $limit = 100; // max requests
    protected int $window = 60; // seconds

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $ip = $request->clientIp() ?: '127.0.0.1';
        $cacheKey = 'rate_limit_' . md5($ip);
        
        $hits = (int)Cache::read($cacheKey, 'default');
        
        if ($hits >= $this->limit) {
            throw new TooManyRequestsException('Rate limit exceeded. Please try again later.');
        }

        // Ideally, cache engine should support TTL increments. 
        // For simplicity using default cache.
        Cache::write($cacheKey, $hits + 1, 'default');
        
        return $handler->handle($request);
    }
}
