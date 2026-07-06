<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Phase 19 - SecurityHeadersMiddleware
 * 
 * Injects hardened HTTP security headers into every response:
 * - Content-Security-Policy  → prevents XSS via whitelisted sources
 * - X-Frame-Options          → prevents Clickjacking
 * - X-Content-Type-Options   → prevents MIME sniffing attacks
 * - Strict-Transport-Security→ enforces HTTPS (HSTS)
 * - Referrer-Policy          → controls referer information leakage
 * - Permissions-Policy       → restricts browser feature access
 */
class SecurityHeadersMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Content-Security-Policy: whitelist known trusted sources only
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' cdn.jsdelivr.net",    // Allow Chart.js CDN
            "style-src 'self' 'unsafe-inline' fonts.googleapis.com",
            "img-src 'self' data: *.googleapis.com",
            "font-src 'self' fonts.gstatic.com",
            "connect-src 'self'",
            "frame-ancestors 'none'",   // Equivalent to X-Frame-Options: DENY
            "form-action 'self'",       // Forms may only POST to own domain
        ]);

        return $response
            ->withHeader('Content-Security-Policy', $csp)
            ->withHeader('X-Frame-Options', 'DENY')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-XSS-Protection', '1; mode=block')
            ->withHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->withHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()')
            // HSTS: enforce HTTPS for 1 year (only in production)
            ->withHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }
}
