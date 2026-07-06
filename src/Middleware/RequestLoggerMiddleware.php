<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Log\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Phase 9 - RequestLoggerMiddleware
 */
class RequestLoggerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $startTime = microtime(true);
        $response = $handler->handle($request);
        $duration = (microtime(true) - $startTime) * 1000;

        Log::debug(sprintf(
            '[RequestLogger] %s %s completed in %.2f ms (Status: %d)',
            $request->getMethod(),
            $request->getUri()->getPath(),
            $duration,
            $response->getStatusCode()
        ));

        return $response;
    }
}
