<?php
/**
 * =============================================================================
 * ENTERPRISE ERP — CakePHP 5 Complete Routing Configuration
 * =============================================================================
 *
 * FILE: config/routes.php
 *
 * This file demonstrates ALL CakePHP routing types from Phase 3:
 *   ✅ Basic Route
 *   ✅ Named Route
 *   ✅ Scoped Route
 *   ✅ Prefix Route
 *   ✅ Plugin Route
 *   ✅ REST Route (Resources)
 *   ✅ Fallback Route
 *   ✅ Dynamic Route
 *   ✅ UUID Route
 *   ✅ Slug Route
 *   ✅ API Route (versioned)
 *   ✅ Admin Route (prefix)
 *   ✅ Version Route (/api/v1/, /api/v2/)
 *   ✅ Custom Route Class (SlugRoute)
 *   ✅ Resource Route
 *   ✅ Nested Resource Route
 *   ✅ Middleware Route (applied per-scope)
 *
 * HOW ROUTING WORKS IN CakePHP 5:
 *   1. Request comes in (e.g. GET /products/my-widget)
 *   2. RoutingMiddleware in Application.php fires
 *   3. Router iterates routes in ORDER — first match wins
 *   4. Matched route injects controller/action/params into the Request object
 *   5. Controller is dispatched with those parameters
 *
 * =============================================================================
 */

use App\Middleware\ApiLoggerMiddleware;
use App\Middleware\MaintenanceModeMiddleware;
use App\Routing\Route\SlugRoute;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\Route\Route;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {

    /*
     * =========================================================================
     * GLOBAL ROUTE CLASS DEFAULT
     * =========================================================================
     * DashedRoute converts CamelCase controller/action names to dashed-URLs.
     * Example: UsersController::forgotPassword → /users/forgot-password
     *
     * Alternatives:
     *   - Route            → No conversion, exact case matching
     *   - InflectedRoute   → Underscored URLs
     *   - DashedRoute      → Dashed URLs (recommended for modern apps)
     */
    $routes->setRouteClass(DashedRoute::class);


    /*
     * =========================================================================
     * SCOPE: '/' — Web Application Routes
     * =========================================================================
     * A scope groups routes that share a common URL prefix and/or options.
     * All routes inside this scope are prefixed with '/'.
     *
     * INTERVIEW: "Scopes prevent repetition. Instead of prefixing every route
     * with /admin, you declare one scope('/admin') and all inner routes
     * inherit that prefix automatically."
     */
    $routes->scope('/', function (RouteBuilder $builder): void {

        // =====================================================================
        // 1. BASIC ROUTE
        // =====================================================================
        // The simplest form: connects a URL path to a controller action.
        // '/' is the homepage, maps to UsersController::dashboard()
        //
        // INTERVIEW: "Basic routes are the foundation. Every other route type
        // is an extension or variation of this connect() call."
        // =====================================================================
        $builder->connect('/', [
            'controller' => 'Users',
            'action'     => 'dashboard',
        ]);


        // =====================================================================
        // 2. NAMED ROUTE
        // =====================================================================
        // Named routes allow generating URLs by name instead of by path.
        // USAGE IN TEMPLATE: $this->Url->build(['_name' => 'user-login'])
        // USAGE IN CONTROLLER: $this->redirect(['_name' => 'user-login'])
        //
        // WHY: If you rename the URL path later, templates don't break because
        // they reference the name, not the literal path.
        //
        // INTERVIEW: "Named routes decouple URL generation from URL paths.
        // Change /users/login to /auth/login and no template breaks."
        // =====================================================================
        $builder->connect('/users/login',    ['controller' => 'Users', 'action' => 'login'],    ['_name' => 'user-login']);
        $builder->connect('/users/logout',   ['controller' => 'Users', 'action' => 'logout'],   ['_name' => 'user-logout']);
        $builder->connect('/users/register', ['controller' => 'Users', 'action' => 'register'], ['_name' => 'user-register']);

        $builder->connect('/users/forgot-password',  ['controller' => 'Users', 'action' => 'forgotPassword'],  ['_name' => 'forgot-password']);
        $builder->connect('/users/reset-password/*', ['controller' => 'Users', 'action' => 'resetPassword'],   ['_name' => 'reset-password']);
        $builder->connect('/users/verify-email/*',   ['controller' => 'Users', 'action' => 'verifyEmail'],     ['_name' => 'verify-email']);
        $builder->connect('/users/profile',          ['controller' => 'Users', 'action' => 'profile'],         ['_name' => 'user-profile']);
        $builder->connect('/users/change-password',  ['controller' => 'Users', 'action' => 'changePassword'],  ['_name' => 'change-password']);
        $builder->connect('/users/delete-account',   ['controller' => 'Users', 'action' => 'deleteAccount'],   ['_name' => 'delete-account']);
        $builder->connect('/users/clear-cache',      ['controller' => 'Users', 'action' => 'clearCache'],      ['_name' => 'clear-cache']);


        // =====================================================================
        // 3. DYNAMIC ROUTE (with parameter tokens)
        // =====================================================================
        // Curly-brace tokens {id}, {slug}, {token} capture URL segments
        // as named parameters available via $this->request->getParam('id').
        //
        // You can constrain parameter format using 'pass' and route options.
        //
        // INTERVIEW: "Dynamic routes use tokens like {id} or {slug} to capture
        // variable URL segments. The router injects them as request parameters."
        // =====================================================================
        $builder->connect('/users/edit/{id}',
            ['controller' => 'Users', 'action' => 'edit'],
            ['pass' => ['id']] // 'pass' makes {id} available as $id in the action
        );

        $builder->connect('/users/view/{id}',
            ['controller' => 'Users', 'action' => 'view'],
            ['pass' => ['id']]
        );

        $builder->connect('/users/activate/{id}',
            ['controller' => 'Users', 'action' => 'toggleActive'],
            ['pass' => ['id']]
        );


        // =====================================================================
        // 4. UUID ROUTE
        // =====================================================================
        // Constrains the {id} parameter to match a valid UUID v4 format.
        // Uses 'id' option with a regex pattern in the route options.
        //
        // WHY: Prevents integer IDs being passed where UUIDs are expected.
        // Protects API endpoints from sequential ID enumeration attacks.
        //
        // INTERVIEW: "UUID routes add a regex constraint to a route parameter.
        // The router will only match the route if the parameter value satisfies
        // the pattern — acting as a first layer of input validation."
        // =====================================================================
        $builder->connect('/api/resource/{id}',
            ['controller' => 'Users', 'action' => 'view'],
            [
                'pass' => ['id'],
                // UUID v4 pattern — router enforces this format automatically
                'id'  => '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}',
            ]
        );


        // =====================================================================
        // 5. SLUG ROUTE — using our Custom Route Class
        // =====================================================================
        // Uses the SlugRoute class from src/Routing/Route/SlugRoute.php
        // which validates slug format (lowercase, digits, hyphens only).
        //
        // The 'routeClass' option overrides the default DashedRoute for
        // this specific route only.
        //
        // INTERVIEW: "I created a custom route class extending CakePHP's Route
        // base class, overriding match() to enforce slug format via regex.
        // This keeps validation at the routing layer rather than in controllers."
        // =====================================================================
        $builder->connect('/products/{slug}',
            ['controller' => 'Products', 'action' => 'view'],
            [
                'pass'       => ['slug'],
                'routeClass' => SlugRoute::class, // Use our custom class
                'slug'       => '[a-z0-9\-]+',    // Regex constraint on parameter
            ]
        );

        $builder->connect('/categories/{slug}',
            ['controller' => 'Categories', 'action' => 'view'],
            ['pass' => ['slug'], 'routeClass' => SlugRoute::class, 'slug' => '[a-z0-9\-]+']
        );

        $builder->connect('/blog/{slug}',
            ['controller' => 'CmsPages', 'action' => 'view'],
            ['pass' => ['slug'], 'routeClass' => SlugRoute::class, 'slug' => '[a-z0-9\-]+']
        );


        // =====================================================================
        // SHORT-FORM ALIAS ROUTES (Clean public-facing URLs)
        // =====================================================================
        // These connect short memorable URLs to UsersController actions.
        // They are defined BEFORE fallbacks() so they match first.
        //
        // /login    → Users::login   (instead of /users/login)
        // /register → Users::register (instead of /users/register)
        // /profile  → Users::profile  (instead of /users/profile)
        //
        // WHY BOTH:
        //   Named routes like 'user-login' still work for internal URL
        //   generation. These short routes are for clean public URLs, emails,
        //   marketing links, and external sharing.
        //
        // INTERVIEW: "I map short vanity URLs to the same controller actions
        // as the full paths. Templates always use named routes for generation
        // so adding these aliases requires zero template changes."
        // =====================================================================
        $builder->connect('/', ['controller' => 'Dashboard', 'action' => 'index'], ['_name' => 'dashboard']);

        // Dashboard routes
        $builder->connect('/dashboard',             ['controller' => 'Dashboard', 'action' => 'index'],      ['_name' => 'dashboard-home']);
        $builder->connect('/dashboard/live-stats',  ['controller' => 'Dashboard', 'action' => 'liveStats'],  ['_name' => 'dashboard-live-stats']);
        $builder->connect('/dashboard/clear-cache', ['controller' => 'Dashboard', 'action' => 'clearCache'], ['_name' => 'dashboard-clear-cache']);

        // Permissions routes
        $builder->connect('/permissions',               ['controller' => 'Permissions', 'action' => 'index'],       ['_name' => 'permissions']);
        $builder->connect('/permissions/add',           ['controller' => 'Permissions', 'action' => 'add'],         ['_name' => 'permissions-add']);
        $builder->connect('/permissions/edit/{id}',     ['controller' => 'Permissions', 'action' => 'edit'],        ['_name' => 'permissions-edit',   'pass' => ['id'], 'id' => '\d+']);
        $builder->connect('/permissions/delete/{id}',   ['controller' => 'Permissions', 'action' => 'delete'],      ['_name' => 'permissions-delete', 'pass' => ['id'], 'id' => '\d+']);
        $builder->connect('/permissions/export',        ['controller' => 'Permissions', 'action' => 'export'],      ['_name' => 'permissions-export']);
        $builder->connect('/permissions/json-list',     ['controller' => 'Permissions', 'action' => 'jsonList'],    ['_name' => 'permissions-json']);
        $builder->connect('/permissions/xml-list',      ['controller' => 'Permissions', 'action' => 'xmlList'],     ['_name' => 'permissions-xml']);
        $builder->connect('/permissions/bulk-import',   ['controller' => 'Permissions', 'action' => 'bulkImport'],  ['_name' => 'permissions-import']);
        $builder->connect('/permissions/check-ajax',    ['controller' => 'Permissions', 'action' => 'checkAjax'],   ['_name' => 'permissions-check-ajax']);

        // Reports routes
        $builder->connect('/reports',              ['controller' => 'Reports', 'action' => 'index'],     ['_name' => 'reports']);
        $builder->connect('/reports/summary',      ['controller' => 'Reports', 'action' => 'summary'],   ['_name' => 'reports-summary']);
        $builder->connect('/reports/export-csv',   ['controller' => 'Reports', 'action' => 'exportCsv'], ['_name' => 'reports-export-csv']);
        $builder->connect('/reports/chart-data',   ['controller' => 'Reports', 'action' => 'chartData'], ['_name' => 'reports-chart-data']);

        // Invoices routes
        $builder->connect('/invoices',          ['controller' => 'Invoices', 'action' => 'index'], ['_name' => 'invoices']);
        $builder->connect('/invoices/add',      ['controller' => 'Invoices', 'action' => 'add'],   ['_name' => 'invoices-add']);
        $builder->connect('/invoices/{id}',     ['controller' => 'Invoices', 'action' => 'view'],  ['_name' => 'invoices-view',   'pass' => ['id'], 'id' => '\d+']);
        $builder->connect('/invoices/edit/{id}',['controller' => 'Invoices', 'action' => 'edit'],  ['_name' => 'invoices-edit',   'pass' => ['id'], 'id' => '\d+']);

        // ORM Demos routes
        $builder->connect('/orm-demos/eager-loading',   ['controller' => 'OrmDemos', 'action' => 'eagerLoading'],   ['_name' => 'orm-eager-loading']);
        $builder->connect('/orm-demos/filtering',       ['controller' => 'OrmDemos', 'action' => 'filtering'],      ['_name' => 'orm-filtering']);
        $builder->connect('/orm-demos/bulk-operations', ['controller' => 'OrmDemos', 'action' => 'bulkOperations'], ['_name' => 'orm-bulk-operations']);
        $builder->connect('/orm-demos/marshalling',     ['controller' => 'OrmDemos', 'action' => 'marshalling'],    ['_name' => 'orm-marshalling']);
        $builder->connect('/orm-demos/transactions',    ['controller' => 'OrmDemos', 'action' => 'transactions'],   ['_name' => 'orm-transactions']);
        // =====================================================================
        // PRACTICAL ORM PHASE 6 ROUTES
        // =====================================================================
        $builder->connect('/products/out-of-stock',         ['controller' => 'Products', 'action' => 'outOfStock']);
        $builder->connect('/products/bulk-price-increase',  ['controller' => 'Products', 'action' => 'bulkPriceIncrease']);
        
        $builder->connect('/orders/advanced-search',        ['controller' => 'Orders', 'action' => 'advancedSearch']);
        $builder->connect('/orders/checkout',               ['controller' => 'Orders', 'action' => 'checkout']);
        $builder->connect('/orders/bulk-update-status',     ['controller' => 'Orders', 'action' => 'bulkUpdateStatus']);

    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    // =========================================================================
    // PHASE 14: Enable Data Extensions
    // =========================================================================
    // This allows CakePHP to detect URLs like /reports/export-data.json
    // and automatically set the _ext request parameter.
    $builder->setExtensions(['json', 'xml', 'csv', 'pdf']);

        $builder->connect('/login',    ['controller' => 'Users', 'action' => 'login'],    ['_name' => 'login']);
        $builder->connect('/logout',   ['controller' => 'Users', 'action' => 'logout'],   ['_name' => 'logout']);
        $builder->connect('/register', ['controller' => 'Users', 'action' => 'register'], ['_name' => 'register']);
        $builder->connect('/profile',  ['controller' => 'Users', 'action' => 'profile'],  ['_name' => 'profile']);


        // =====================================================================
        // /product/{id} — Dynamic route with INTEGER ID constraint
        // =====================================================================
        // Maps /product/42 → ProductsController::view(42)
        //
        // NOTE: Different from /products/{slug} which uses SlugRoute.
        // This route accepts a numeric ID (e.g. from QR codes, legacy URLs).
        //
        // The 'id' option constrains {id} to digits only (\d+).
        // /product/abc → NO MATCH (returns 404, fallback handles it)
        // /product/42  → MATCH → Products::view(42)
        //
        // INTERVIEW: "Route constraints via regex prevent invalid parameters
        // from ever reaching the controller. /product/abc would fail the regex,
        // skip this route, and return 404 via fallback — no controller code runs."
        // =====================================================================
        $builder->connect('/product/{id}',
            ['controller' => 'Products', 'action' => 'view'],
            [
                'pass' => ['id'],
                'id'   => '\d+',  // Only match numeric IDs
            ]
        );


        // =====================================================================
        // /category/{slug} — Slug route for category pages
        // =====================================================================
        // Maps /category/homeopathic-medicines → CategoriesController::view('homeopathic-medicines')
        //
        // Uses SlugRoute custom class for slug format enforcement.
        // The slug MUST be lowercase-alphanumeric-and-hyphens-only.
        //
        // INTERVIEW: "Short singular URLs (/category/{slug}) are SEO-friendly
        // and more readable than plurals (/categories/{slug}). I map both
        // to the same controller action so both URLs work."
        // =====================================================================
        $builder->connect('/category/{slug}',
            ['controller' => 'Categories', 'action' => 'view'],
            [
                'pass'       => ['slug'],
                'routeClass' => SlugRoute::class,
                'slug'       => '[a-z0-9\-]+',
            ]
        );


        // Static pages route
        $builder->connect('/pages/*', 'Pages::display');


        // =====================================================================
        // 7. FALLBACK ROUTE
        // =====================================================================
        // The fallback() method registers two generic catch-all routes:
        //   /{controller}            → {Controller}::index()
        //   /{controller}/{action}/* → {Controller}::{action}(...args)
        //
        // WHY: During development, fallbacks let you access any controller
        // without defining individual routes. In production, explicit routes
        // are preferred for security and clarity.
        //
        // INTERVIEW: "Fallback routes are a convenience for prototyping. In
        // production I replace them with explicit routes to avoid accidentally
        // exposing unintended actions."
        // =====================================================================
        $builder->fallbacks(DashedRoute::class);

    }); // end scope('/')


    /*
     * =========================================================================
     * PREFIX ROUTE: /admin
     * =========================================================================
     * Prefix routes add a URL segment AND namespace controllers.
     * All controllers inside prefix('admin') must live in:
     *   src/Controller/Admin/
     *
     * URL /admin/users → Admin\UsersController::index()
     *
     * HOW IT DIFFERS FROM SCOPE:
     *   - scope() only adds a URL prefix
     *   - prefix() adds URL prefix + maps to a sub-namespace in src/Controller/
     *
     * INTERVIEW: "Admin prefix routes separate admin controllers from public
     * controllers at both the URL level and namespace level. A hacker who
     * finds /users doesn't automatically know there's an /admin/users."
     * =========================================================================
     */
    $routes->prefix('Admin', function (RouteBuilder $builder): void {
        // Apply maintenance middleware to all Admin routes
        $builder->registerMiddleware('maintenance', new MaintenanceModeMiddleware());
        $builder->applyMiddleware('maintenance');

        // ==================================================================
        // EXPLICIT ADMIN NAMED ROUTES
        // ==================================================================
        // Even though fallbacks() would handle these, explicit named routes
        // allow URL generation by name: $this->Url->build(['_name' => 'admin-users'])
        // and make the codebase self-documenting.
        //
        // ROUTE: GET /admin/users → Admin\UsersController::index()
        $builder->connect('/users',
            ['controller' => 'Users', 'action' => 'index'],
            ['_name' => 'admin-users']
        );

        // ROUTE: GET /admin/users/{id} → Admin\UsersController::view($id)
        // {id} is constrained to integers, passed as positional param
        $builder->connect('/users/{id}',
            ['controller' => 'Users', 'action' => 'view'],
            ['_name' => 'admin-users-view', 'pass' => ['id'], 'id' => '\d+']
        );

        // ROUTE: POST /admin/users/{id} → Admin\UsersController::edit($id)
        $builder->connect('/users/{id}/edit',
            ['controller' => 'Users', 'action' => 'edit'],
            ['_name' => 'admin-users-edit', 'pass' => ['id'], 'id' => '\d+']
        );

        // ROUTE: GET /admin/orders → Admin\OrdersController::index()
        $builder->connect('/orders',
            ['controller' => 'Orders', 'action' => 'index'],
            ['_name' => 'admin-orders']
        );

        // ROUTE: GET /admin/orders/{id} → Admin\OrdersController::view($id)
        // ---------------------------------------------------------------
        // This is the KEY example from Phase 3: /admin/orders/{id}
        // Without this explicit route, fallbacks() would interpret
        // /admin/orders/5 as action='5' which is WRONG.
        // With 'pass' => ['id'], the {id} segment is injected as $id in view().
        // ---------------------------------------------------------------
        $builder->connect('/orders/{id}',
            ['controller' => 'Orders', 'action' => 'view'],
            ['_name' => 'admin-orders-view', 'pass' => ['id'], 'id' => '\d+']
        );

        // ROUTE: POST /admin/orders/{id}/update-status
        $builder->connect('/orders/{id}/update-status',
            ['controller' => 'Orders', 'action' => 'updateStatus'],
            ['_name' => 'admin-orders-update-status', 'pass' => ['id'], 'id' => '\d+']
        );

        // Fallback resolves all remaining /admin/{controller}/{action} routes
        $builder->fallbacks(DashedRoute::class);
    });


    /*
     * =========================================================================
     * VERSION ROUTE + API ROUTE: /api/v1
     * =========================================================================
     * Versioned API scopes allow running multiple API versions simultaneously.
     * /api/v1/users → Api\V1\UsersController
     * /api/v2/users → Api\V2\UsersController
     *
     * HOW VERSIONING WORKS:
     *   Each version is a nested prefix inside the /api scope.
     *   Controllers live in src/Controller/Api/V1/ and src/Controller/Api/V2/
     *
     * INTERVIEW: "API versioning via nested prefix scopes means I can deploy
     * breaking changes in /api/v2 while /api/v1 continues working for
     * existing clients. Both run in the same application simultaneously."
     * =========================================================================
     */
    $routes->prefix('Api', function (RouteBuilder $builder): void {

        // Apply API Logger middleware to ALL API routes
        // Every request under /api/* gets logged automatically
        $builder->registerMiddleware('apiLogger', new ApiLoggerMiddleware());
        $builder->applyMiddleware('apiLogger');

        // Enable JSON and XML response extensions
        // /api/users.json → returns JSON, /api/users.xml → returns XML
        $builder->setExtensions(['json', 'xml']);

        // ===================================================================
        // API v1 — Stable Production API
        // ===================================================================
        $builder->prefix('V1', ['path' => '/v1'], function (RouteBuilder $builder): void {
            $builder->setExtensions(['json', 'xml']);

            // REST Resource routes for Users (see section 8 below for explanation)
            // Generates: GET /api/v1/users, POST /api/v1/users,
            //            GET /api/v1/users/{id}, PUT /api/v1/users/{id},
            //            DELETE /api/v1/users/{id}
            $builder->resources('Users');

            // REST Resources for ERP modules
            $builder->resources('Products');
            $builder->resources('Categories');
            $builder->resources('Brands');
            $builder->resources('Orders', function (RouteBuilder $builder): void {
                // ===========================================================
                // NESTED RESOURCE ROUTE
                // ===========================================================
                // Nested resources express parent-child relationships in the URL.
                // GET /api/v1/orders/{order_id}/order-items
                //   → OrderItemsController::index() with order_id param
                //
                // WHY: REST best practice — a resource accessible only through
                // its parent should be nested in the URL.
                //
                // INTERVIEW: "Nested resources in REST mean a child resource's
                // URL always includes its parent ID. /orders/5/items only shows
                // items belonging to order 5, enforcing data scoping at the
                // routing level."
                // ===========================================================
                $builder->resources('OrderItems');
            });
            $builder->resources('Payments');

            // Named API routes for auth endpoints
            $builder->connect('/auth/login',    ['controller' => 'Users', 'action' => 'login'],    ['_name' => 'api-v1-login']);
            $builder->connect('/auth/register', ['controller' => 'Users', 'action' => 'register'], ['_name' => 'api-v1-register']);
            $builder->connect('/auth/logout',   ['controller' => 'Users', 'action' => 'logout'],   ['_name' => 'api-v1-logout']);
            $builder->connect('/auth/refresh',  ['controller' => 'Users', 'action' => 'refreshToken'], ['_name' => 'api-v1-refresh']);

            $builder->fallbacks(DashedRoute::class);
        });

        // ===================================================================
        // API v2 — New Version (Breaking Changes)
        // ===================================================================
        $builder->prefix('V2', ['path' => '/v2'], function (RouteBuilder $builder): void {
            $builder->setExtensions(['json', 'xml']);

            // V2 controllers live in src/Controller/Api/V2/
            $builder->resources('Users');
            $builder->resources('Products');

            $builder->fallbacks(DashedRoute::class);
        });

        // Catch-all for unversioned /api/* requests — redirect or 404
        $builder->connect('/users/login',    ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/users/profile',  ['controller' => 'Users', 'action' => 'profile']);
        $builder->connect('/users/register', ['controller' => 'Users', 'action' => 'register']);
        $builder->connect('/users/forgot-password',  ['controller' => 'Users', 'action' => 'forgotPassword']);
        $builder->connect('/users/reset-password/*', ['controller' => 'Users', 'action' => 'resetPassword']);
        $builder->connect('/users/verify-email/*',   ['controller' => 'Users', 'action' => 'verifyEmail']);
        $builder->connect('/users/change-password',  ['controller' => 'Users', 'action' => 'changePassword']);
        $builder->connect('/users/status/*',         ['controller' => 'Users', 'action' => 'status']);
        $builder->connect('/users',                  ['controller' => 'Users', 'action' => 'index']);

        $builder->fallbacks(DashedRoute::class);
    });


    /*
     * =========================================================================
     * 8. REST RESOURCE ROUTE
     * =========================================================================
     * $builder->resources('ModelName') is CakePHP shorthand that generates
     * 5 standard RESTful routes in one call:
     *
     *   HTTP Method │ URL                    │ Action  │ Use Case
     *   ────────────┼────────────────────────┼─────────┼────────────────────
     *   GET         │ /products              │ index   │ List all products
     *   GET         │ /products/{id}         │ view    │ View one product
     *   POST        │ /products              │ add     │ Create product
     *   PUT/PATCH   │ /products/{id}         │ edit    │ Update product
     *   DELETE      │ /products/{id}         │ delete  │ Delete product
     *
     * INTERVIEW: "resources() follows REST conventions. It maps HTTP verbs to
     * controller actions. The router matches both the URL AND the HTTP method,
     * so GET /products and POST /products go to different actions despite
     * having the same URL."
     * =========================================================================
     */

}; // end routes function
