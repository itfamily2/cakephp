<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\View\View;

/**
 * Application View — Phase 10: View Events
 *
 * VIEW EVENT LIFECYCLE:
 *   1. View.beforeRender  → fires before the template file is rendered
 *   2. [TEMPLATE RENDERS] → the .php template file executes
 *   3. View.afterRender   → fires after the template is fully rendered
 *
 * These events are fired by the View class itself, not the controller.
 * They're useful for:
 *   - Adding global helpers (beforeRender)
 *   - Setting theme/layout dynamically (beforeRender)
 *   - Post-processing rendered HTML (afterRender) — e.g. minification
 *   - Logging render times (afterRender)
 *
 * INTERVIEW: "View events are different from Controller.beforeRender.
 *   Controller.beforeRender fires before the View is even invoked.
 *   View.beforeRender fires inside the View, just before the template
 *   file is included. I use View events when I need to manipulate
 *   the rendering process itself, not just set variables."
 *
 * @link https://book.cakephp.org/5/en/views.html#the-app-view
 * @extends \Cake\View\View<\App\View\AppView>
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like adding helpers.
     *
     * e.g. `$this->addHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        // =====================================================================
        // PHASE 12: Core Helpers
        // =====================================================================
        // These are standard CakePHP helpers loaded globally for all views.
        $this->loadHelper('Html');
        $this->loadHelper('Form');
        $this->loadHelper('Paginator');
        $this->loadHelper('Time');
        $this->loadHelper('Number');
        $this->loadHelper('Text');

        // =====================================================================
        // PHASE 12: Custom ERP Helpers
        // =====================================================================
        // Loading custom helpers created for this specific application.
        $this->loadHelper('ErpFormat');
        $this->loadHelper('StatusBadge');
    }

    // =========================================================================
    // PHASE 10: View.beforeRender Event
    // =========================================================================
    /**
     * Fires BEFORE the template file (.php) is rendered.
     *
     * Use for:
     *   - Loading global helpers that every template needs
     *   - Setting theme based on user preferences
     *   - Injecting CDN asset URLs
     *   - Adding breadcrumb data
     *
     * INTERVIEW: "I use View.beforeRender to load helpers that are needed
     *   universally — like a custom FormHelper override or an AssetHelper
     *   that handles CDN URLs. This way individual templates don't need
     *   to load them manually."
     *
     * @param \Cake\Event\EventInterface $event The beforeRender event
     * @return void
     */
    public function beforeRender(EventInterface $event): void
    {
        // Log the event firing
        Log::debug(sprintf(
            '[PHASE 10] View.beforeRender — Template: %s, Layout: %s',
            $this->getTemplate() ?: 'unknown',
            $this->getLayout() ?: 'default'
        ));

        // Example: Inject a global variable available in ALL templates
        $this->set('appVersion', '1.0.0-phase10');
    }

    // =========================================================================
    // PHASE 10: View.afterRender Event
    // =========================================================================
    /**
     * Fires AFTER the template has been fully rendered to a string.
     *
     * Use for:
     *   - Post-processing the rendered HTML (minification, injection)
     *   - Logging render performance metrics
     *   - Adding analytics tracking snippets
     *   - Caching the rendered output
     *
     * INTERVIEW: "View.afterRender is useful for HTML post-processing.
     *   For example, I could minify inline CSS/JS, inject CSP nonces,
     *   or measure template render time for performance monitoring.
     *   The rendered content is available but not yet sent to the client."
     *
     * @param \Cake\Event\EventInterface $event The afterRender event
     * @return void
     */
    public function afterRender(EventInterface $event): void
    {
        Log::debug(sprintf(
            '[PHASE 10] View.afterRender — Template: %s rendered successfully',
            $this->getTemplate() ?: 'unknown'
        ));

        // Example: You could post-process the rendered content here
        // $content = $this->Blocks->get('content');
        // $minified = $this->minifyHtml($content);
        // $this->Blocks->set('content', $minified);
    }
}
