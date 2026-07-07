<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Event\Listener\NotificationListener;
use App\Middleware\HostHeaderMiddleware;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventManagerInterface;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        // By default, does not allow fallback classes.
        FactoryLocator::add('Table', (new TableLocator())->allowFallbackClass(false));

        // =====================================================================
        // PHASE 10: Application.bootstrap Event
        // =====================================================================
        // bootstrap() fires after all config files are loaded, before any
        // request handling. This is the correct place to register global
        // event listeners that should be available throughout the entire
        // application lifecycle.
        //
        // WHY HERE AND NOT IN events():
        //   bootstrap() runs for ALL request types (HTTP, CLI, tests).
        //   events() is the preferred CakePHP 5 way but bootstrap() is
        //   where you'd typically register listeners in older apps.
        //   We demonstrate both approaches.
        // =====================================================================
        \Cake\Log\Log::info('[PHASE 10] Application.bootstrap — Application bootstrapped, listeners registered');
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Validate Host header to prevent Host Header Injection attacks.
            // In production, ensures App.fullBaseUrl is configured and validates
            // the incoming Host header against it.
            ->add(new HostHeaderMiddleware())

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance.
            // See https://github.com/CakeDC/cakephp-cached-routing
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/5/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/5/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
            ->add((new CsrfProtectionMiddleware([
                'httponly' => true,
            ]))->skipCheckCallback(function (\Psr\Http\Message\ServerRequestInterface $request) {
                return str_starts_with($request->getUri()->getPath(), '/api');
            }))

            // Add AuthenticationMiddleware.
            ->add(new AuthenticationMiddleware($this))

            // Add AuthorizationMiddleware.
            // Wrap in a closure to skip authorization checks for DebugKit plugin routes
            ->add(function ($request, $handler) {
                if (str_starts_with($request->getUri()->getPath(), '/debug-kit/')) {
                    return $handler->handle($request);
                }
                $middleware = new AuthorizationMiddleware($this);
                return $middleware->process($request, $handler);
            })

            // =================================================================
            // PHASE 19: Security Middlewares
            // =================================================================
            
            // Force HTTPS in production
            ->add(new \App\Middleware\ForceHttpsMiddleware())
            
            // Hardened Security Headers (CSP, XSS, HSTS)
            ->add(new \App\Middleware\SecurityHeadersMiddleware())

            // 1. Maintenance Mode (check early)
            ->add(new \App\Middleware\MaintenanceModeMiddleware())
            
            // 2. Request Logger (log all requests)
            ->add(new \App\Middleware\RequestLoggerMiddleware())
            
            // 3. API Logger (log only API endpoints)
            ->add(new \App\Middleware\ApiLoggerMiddleware())
            
            // 4. Rate Limiter (throttle abusive IPs)
            ->add(new \App\Middleware\RateLimiterMiddleware())
            
            // 5. Encrypted Cookie (Core middleware for secure cookies)
            ->add(new \Cake\Http\Middleware\EncryptedCookieMiddleware(
                ['secrets', 'preferences'], // Cookie names to encrypt
                Configure::read('Security.cookieKey') ?: 'secret-key-that-should-be-long-and-random'
            ));

        return $middlewareQueue;
    }

    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $path = $request->getUri()->getPath();
        $isApi = str_starts_with($path, '/api');

        $authenticationService = new AuthenticationService([
            'unauthenticatedRedirect' => $isApi ? null : '/users/login',
            'queryParam' => 'redirect',
        ]);

        // Load authenticators
        if ($isApi) {
            // PHASE 16: JWT Authentication
            $authenticationService->loadAuthenticator('Authentication.Jwt', [
                'secretKey' => \Cake\Utility\Security::getSalt(),
                'algorithm' => 'HS256',
                'returnPayload' => false
            ]);
            $authenticationService->loadIdentifier('Authentication.JwtSubject');
        }

        $authenticationService->loadAuthenticator('Authentication.Session');
        
        $authenticationService->loadAuthenticator('Authentication.Form', [
            'fields' => [
                'username' => 'username',
                'password' => 'password',
            ],
            'loginUrl' => '/users/login',
            'identifier' => [
                'className' => 'Authentication.Password',
                'fields' => [
                    'username' => ['username', 'email'],
                    'password' => 'password',
                ],
            ],
        ]);

        $authenticationService->loadAuthenticator('Authentication.Cookie', [
            'fields' => [
                'username' => 'username',
                'password' => 'password',
            ],
            'loginUrl' => '/users/login',
            'cookie' => [
                'name' => 'RememberMe',
                'expires' => new \DateTime('+30 days'),
            ],
            'identifier' => [
                'className' => 'Authentication.Password',
                'fields' => [
                    'username' => ['username', 'email'],
                    'password' => 'password',
                ],
            ],
        ]);

        return $authenticationService;
    }

    /**
     * Get the Authorization Service.
     */
    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        $mapResolver = new \Authorization\Policy\MapResolver();
        $mapResolver->map(\Cake\Http\ServerRequest::class, \App\Policy\RequestPolicy::class);

        $ormResolver = new \Authorization\Policy\OrmResolver();
        $resolverCollection = new \Authorization\Policy\ResolverCollection([$mapResolver, $ormResolver]);
        $resolver = new \App\Policy\FallbackPolicyResolver($resolverCollection);

        return new AuthorizationService($resolver);
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/5/en/development/dependency-injection.html#dependency-injection
     */
    /**
     * Phase 10: Application.buildContainer Event
     *
     * services() is called when the DI container is being built.
     * This corresponds to the Application.buildContainer event.
     *
     * Use for: registering services, swapping implementations for testing,
     * binding interfaces to concrete classes.
     *
     * INTERVIEW: "services() is CakePHP 5's dependency injection hook.
     *   It fires during container construction. I use it to register
     *   service classes that controllers/commands can type-hint for injection."
     */
    public function services(ContainerInterface $container): void
    {
        // Allow your Tables to be dependency injected
        //$container->delegate(new \Cake\ORM\Locator\TableContainer());

        // Phase 10: Log container build event
        \Cake\Log\Log::info('[PHASE 10] Application.buildContainer (services) — DI container being built');
    }

    /**
     * Phase 10: Register custom event listeners
     *
     * events() is the PREFERRED CakePHP 5 way to register event listeners.
     * It receives the application-level EventManager and returns it.
     *
     * WHY events() OVER bootstrap():
     *   events() is explicitly designed for listener registration.
     *   It's called after bootstrap() and provides the EventManager directly.
     *   CakePHP's documentation recommends this approach.
     *
     * @param \Cake\Event\EventManagerInterface $eventManager
     * @return \Cake\Event\EventManagerInterface
     * @link https://book.cakephp.org/5/en/core-libraries/events.html#registering-listeners
     */
    public function events(EventManagerInterface $eventManager): EventManagerInterface
    {
        // =====================================================================
        // PHASE 10: Register the NotificationListener
        // =====================================================================
        // This single line registers ALL 5 custom domain event handlers.
        // The listener's implementedEvents() method tells the EventManager
        // which events to route to which methods.
        // =====================================================================
        $eventManager->on(new NotificationListener());

        return $eventManager;
    }
}
