<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cake\Http\Response;

/**
 * MaintenanceModeMiddleware (Phase 3: Custom Middleware Route + Phase 9: Security)
 *
 * WHAT IT DOES:
 *   Intercepts every HTTP request BEFORE it reaches the router or controller.
 *   If maintenance mode is active (controlled by a config file flag), it
 *   immediately returns a 503 Service Unavailable response to all visitors,
 *   except those connecting from whitelisted IP addresses.
 *
 * WHY IT EXISTS:
 *   When deploying updates or running database migrations, you need to shut
 *   down the application cleanly. A maintenance middleware is the correct
 *   CakePHP 5 pattern — it lives in the PSR-15 middleware pipeline and runs
 *   before ANY framework code executes.
 *
 * HOW THE MIDDLEWARE PIPELINE WORKS:
 *   Request → [MaintenanceModeMiddleware] → [RoutingMiddleware] → [Controller]
 *   If maintenance is ON, the request never reaches the Router.
 *
 * INTERVIEW TALKING POINT:
 *   "PSR-15 middleware is a double-pass pipeline. Each middleware receives the
 *    request and a handler. It can either modify the request and call
 *    $handler->handle($request) to pass it forward, OR return its own Response
 *    to short-circuit the pipeline — which is exactly what we do for maintenance."
 */
class MaintenanceModeMiddleware implements MiddlewareInterface
{
    /**
     * File path that acts as the maintenance mode toggle switch.
     * Create this file to enable maintenance; delete it to disable.
     */
    private string $maintenanceFile;

    /**
     * IP addresses that bypass maintenance mode (developers, admins).
     */
    private array $allowedIps;

    public function __construct(array $allowedIps = [])
    {
        // 1. The flag file lives in the application's tmp directory
        $this->maintenanceFile = TMP . 'maintenance.lock';

        // 2. Merge default developer localhost IPs with any provided IPs
        $this->allowedIps = array_merge(['127.0.0.1', '::1'], $allowedIps);
    }

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request The incoming request object
     * @param RequestHandlerInterface $handler The next middleware/controller in the pipeline
     * @return ResponseInterface The HTTP response
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        // 1. Check if the maintenance lock file exists
        if (file_exists($this->maintenanceFile)) {

            // 2. Get the client's IP address from the request
            $clientIp = $request->getAttribute('clientIp')
                ?? ($_SERVER['REMOTE_ADDR'] ?? '');

            // 3. Allow whitelisted IPs to pass through even during maintenance
            if (!in_array($clientIp, $this->allowedIps, true)) {

                // 4. Build a 503 response with a clear maintenance message
                $response = new Response();
                return $response
                    ->withStatus(503)
                    ->withHeader('Content-Type', 'text/html; charset=UTF-8')
                    ->withHeader('Retry-After', '3600') // Hint to browsers/crawlers to retry in 1 hour
                    ->withStringBody('
                        <!DOCTYPE html>
                        <html>
                        <head><title>Maintenance Mode</title></head>
                        <body style="font-family:sans-serif;text-align:center;padding:80px;">
                            <h1>🔧 Under Maintenance</h1>
                            <p>We are performing scheduled maintenance. Please check back soon.</p>
                        </body>
                        </html>
                    ');
            }
        }

        // 5. Maintenance is off OR client is whitelisted — pass request forward
        return $handler->handle($request);
    }
}
