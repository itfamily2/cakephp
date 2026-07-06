<?php
declare(strict_types=1);

namespace App\Routing\Route;

use Cake\Routing\Route\Route;

/**
 * SlugRoute - Custom Route Class (Phase 3: Custom Route Class)
 *
 * WHAT IT DOES:
 *   A custom route class enforces that the {slug} parameter only matches
 *   URL-safe slug strings (lowercase letters, digits, hyphens).
 *
 * WHY IT EXISTS:
 *   CakePHP's default Route accepts any string as a parameter. When building
 *   SEO-friendly URLs like /products/my-awesome-product, we want to guarantee
 *   the router only matches properly formatted slugs and rejects raw IDs or
 *   injected characters automatically.
 *
 * HOW IT WORKS:
 *   We override the `match()` method so that if the slug value contains
 *   invalid characters, the route refuses to match — causing the router to
 *   try the next route in the collection.
 *
 * INTERVIEW TALKING POINT:
 *   "I created a custom route class by extending Cake\Routing\Route\Route and
 *    overriding match() to enforce a regex pattern on the slug parameter.
 *    This lets the router itself validate URL shape, removing that responsibility
 *    from the controller."
 */
class SlugRoute extends Route
{
    /**
     * Slug regex pattern — only lowercase alphanumeric and hyphens allowed.
     */
    private const SLUG_PATTERN = '/^[a-z0-9\-]+$/';

    /**
     * Override match() to enforce slug format validation.
     *
     * @param array $url    The URL array being matched (e.g. ['controller' => 'Products', 'slug' => 'my-product'])
     * @param array $context Current request context (host, method, etc.)
     * @return string|false  Returns the matched URL string, or false if slug is invalid
     */
    public function match(array $url, array $context = []): ?string
    {
        // 1. Check if the URL array contains a 'slug' key
        if (isset($url['slug'])) {
            // 2. Enforce that slug only contains valid characters
            if (!preg_match(self::SLUG_PATTERN, $url['slug'])) {
                // 3. Return null = tell the router "this route does not match"
                return null;
            }
        }

        // 4. Delegate to parent class for the actual URL string building
        return parent::match($url, $context);
    }
}
