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

        // Check if user is authenticated and share with view
        $identity = $this->Authentication->getIdentity();
        $this->set('currentUser', $identity ? $identity->getOriginalData() : null);

        // Share site settings with all views
        try {
            $settingsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Settings');
            $settings = [];
            foreach ($settingsTable->find()->all() as $s) {
                $settings[$s->key] = $s->value;
            }
            $this->set('siteSettings', $settings);
            
            // Define settings as Configure values if not already defined
            foreach ($settings as $key => $val) {
                if (!\Cake\Core\Configure::check($key)) {
                    \Cake\Core\Configure::write($key, $val);
                }
            }
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

    /**
     * Helper: Log user or system activity.
     */
    protected function logActivity(?int $userId, string $action, string $description): void
    {
        try {
            $activityLogsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('ActivityLogs');
            $log = $activityLogsTable->newEmptyEntity();
            $log->user_id = $userId;
            $log->action = $action;
            $log->description = $description;
            $log->ip_address = $this->request->clientIp() ?: '127.0.0.1';
            $activityLogsTable->save($log);
        } catch (\Exception $e) {
            // Fail silently in case of issues
        }
    }
}
