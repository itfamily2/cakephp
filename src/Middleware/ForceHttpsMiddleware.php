<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Phase 19 - ForceHttpsMiddleware
 * 
 * Redirects all HTTP traffic to HTTPS in production.
 * In development (localhost), this is bypassed to avoid breaking local workflow.
 */
class ForceHttpsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $host = $request->getUri()->getHost();
        $scheme = $request->getUri()->getScheme();
        $isLocalhost = in_array($host, ['localhost', '127.0.0.1', '::1']);

        // Only redirect if not already HTTPS and not running locally
        if ($scheme !== 'https' && !$isLocalhost) {
            $httpsUri = $request->getUri()->withScheme('https')->withPort(443);
            
            $response = new \Cake\Http\Response(['status' => 301]);
            return $response->withHeader('Location', (string)$httpsUri);
        }

        return $handler->handle($request);
    }
}
