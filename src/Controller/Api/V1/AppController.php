<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\View\JsonView;

/**
 * Phase 16 - API Base Controller
 * All API controllers inherit from this.
 */
class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
        
        // Force JSON response for all API actions
        $this->viewBuilder()->setClassName('Json');
    }

    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        
        // API Error Handling Setup
        // Prevent form tampering protection for API requests
        if ($this->components()->has('FormProtection')) {
            $this->components()->unload('FormProtection');
        }
    }
}
