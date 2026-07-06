<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cake\Log\Log;

/**
 * ApiLoggerMiddleware (Phase 3: Middleware Route + Phase 23: Logging)
 *
 * WHAT IT DOES:
 *   Intercepts every request that enters an API route scope and logs:
 *   - HTTP Method (GET, POST, PUT, DELETE)
 *   - Request URI
 *   - Client IP Address
 *   - Response HTTP Status Code
 *   - Time taken to process the request in milliseconds
 *
 * WHY IT EXISTS:
 *   API monitoring requires centralized logging of all inbound requests.
 *   Instead of putting log calls inside every API controller action (violating
 *   DRY), we place one middleware in the API scope's pipeline that handles
 *   it for ALL API routes automatically.
 *
 * HOW IT WORKS:
 *   The middleware wraps the request handler call. It records a start timestamp
 *   BEFORE calling $handler->handle() and logs the response details AFTER
 *   it returns. This is the "before/after" middleware pattern.
 *
 * INTERVIEW TALKING POINT:
 *   "Middleware's power is that it wraps the entire request/response cycle.
 *    By recording time before $handler->handle() and measuring after, I get
 *    accurate server-side latency for every API call without touching any
 *    controller code."
 */
class ApiLoggerMiddleware implements MiddlewareInterface
{
    /**
     * Process the request, delegate to next handler, then log the result.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        // 1. Record the start time with microsecond precision
        $startTime = microtime(true);

        // 2. Pass request to the next middleware / controller (let it process)
        $response = $handler->handle($request);

        // 3. Calculate elapsed time in milliseconds
        $elapsedMs = round((microtime(true) - $startTime) * 1000, 2);

        // 4. Extract request details for the log entry
        $method  = $request->getMethod();               // GET, POST, etc.
        $uri     = (string)$request->getUri();          // Full URL
        $ip      = $request->getAttribute('clientIp')
                   ?? ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $status  = $response->getStatusCode();          // 200, 401, 404, etc.

        // 5. Write structured log entry to the 'api' log channel
        //    Configure 'api' channel in config/app.php Log section
        Log::write('info',
            "[API] {$method} {$uri} | IP: {$ip} | Status: {$status} | Time: {$elapsedMs}ms",
            ['scope' => ['api']]
        );

        // 6. Return the original response unchanged
        return $response;
    }
}
