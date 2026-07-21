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
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Log\Log;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
        $this->loadComponent('FormProtection');
        
        // Load Phase 11 Custom Components
        $this->loadComponent('Auth');
        $this->loadComponent('Upload');
        $this->loadComponent('Notification');
        $this->loadComponent('Activity');
        $this->loadComponent('Settings');
        $this->loadComponent('Permission');
        $this->loadComponent('Audit', [
            'auditActions' => ['edit', 'delete', 'add', 'checkout']
        ]);
    }

    /**
     * beforeFilter callback.
     *
     * @param \Cake\Event\EventInterface $event The event.
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        // Check if user is authenticated and share with view using AuthComponent
        $this->set('currentUser', $this->Auth->user());

        // Share site settings with all views using SettingsComponent
        try {
            // Note: SettingsComponent caches to Configure automatically during read/write
            $settingsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Settings');
            $settings = [];
            foreach ($settingsTable->find()->all() as $s) {
                // Pre-populate Configure for this request
                $this->Settings->read($s->key, $s->value);
                $settings[$s->key] = $s->value;
            }
            $this->set('siteSettings', $settings);
        } catch (\Exception $e) {
            $this->set('siteSettings', []);
        }

        // Determine if this is a public action to bypass/skip authorization checks
        $controller = strtolower($this->request->getParam('controller') ?? '');
        $action = strtolower($this->request->getParam('action') ?? '');
        $prefix = strtolower($this->request->getParam('prefix') ?? '');

        $publicActions = [
            'users' => ['login', 'register', 'forgotpassword', 'resetpassword', 'verifyemail', 'logout', 'sociallogin'],
            'cmspages' => ['view'],
            'contactenquiries' => ['add'],
        ];

        $isPublic = isset($publicActions[$controller]) && in_array($action, $publicActions[$controller]) && !$prefix;

        if ($isPublic) {
            $this->Authorization->skipAuthorization();
        } else {
            if ($this->Authentication->getIdentity() !== null) {
                $this->Authorization->authorize($this->request);
            } else {
                $this->Authorization->skipAuthorization();
            }
        }
    }

    // Removed logActivity as we now use ActivityComponent

    // =========================================================================
    // PHASE 10: Controller.beforeRender Event
    // =========================================================================
    /**
     * Fires AFTER the action method, BEFORE the view renders.
     *
     * Use for: setting global view variables, switching layouts,
     * injecting helpers, modifying serialized data for JSON responses.
     *
     * INTERVIEW: "beforeRender() is where I set variables that every template
     *   needs — like the current user, site name, notification count.
     *   It runs after the action so action-specific variables are already set."
     */
    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);

        // Share timestamp for debugging
        $this->set('renderTimestamp', date('Y-m-d H:i:s'));

        // Automatically disable layout for all AJAX requests to return raw view HTML for modals
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->disableAutoLayout();
        }
    }

    // =========================================================================
    // PHASE 10: Controller.shutdown / Controller.afterFilter Event
    // =========================================================================
    /**
     * Fires AFTER the response has been fully prepared and is about to be sent.
     *
     * Use for: logging, cleanup, queue dispatching, analytics.
     * This is the LAST controller event in the lifecycle.
     *
     * INTERVIEW: "afterFilter() is the cleanup hook. I use it for post-response
     *   tasks like logging request duration, flushing buffers, or dispatching
     *   background jobs that don't need to block the response."
     */
    public function afterFilter(EventInterface $event): void
    {
        parent::afterFilter($event);

        // Log every request for monitoring (in production, use a proper logger)
        Log::debug(sprintf(
            '[PHASE 10] Controller.shutdown — %s::%s completed',
            $this->request->getParam('controller'),
            $this->request->getParam('action')
        ));
    }
}
