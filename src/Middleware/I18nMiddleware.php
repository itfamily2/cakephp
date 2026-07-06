<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cake\I18n\I18n;
use Cake\I18n\DateTime;

/**
 * Phase 24 - I18nMiddleware (Enterprise Features)
 * 
 * Automatically applies User-specific Localization, Timezone, and Currency preferences
 * per HTTP request.
 */
class I18nMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 1. Detect Identity
        $identity = $request->getAttribute('identity');

        if ($identity) {
            // 2. Localization
            $locale = $identity->get('locale') ?? 'en_GB';
            I18n::setLocale($locale);

            // 3. Timezone
            $timezone = $identity->get('timezone') ?? 'Asia/Kolkata';
            date_default_timezone_set($timezone);
            // Configure CakePHP DateTime to use the user's timezone for display
            // (Note: Cake 5 frozen dates are immutable, this affects new instances)

            // 4. Currency could be stored in request attributes for helpers to use
            $currency = $identity->get('currency') ?? 'INR';
            $request = $request->withAttribute('currency', $currency);
        }

        return $handler->handle($request);
    }
}
