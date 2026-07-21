<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\View\JsonView;

/**
 * Phase 16 - API Base Controller (CakePHP 5 compatible)
 *
 * INTERVIEW NOTE:
 * In CakePHP 4, RequestHandlerComponent was used to auto-detect JSON.
 * In CakePHP 5, it was REMOVED. Instead:
 *   - viewClasses() tells CakePHP which View class handles which content type.
 *   - JsonView automatically reads all set() variables and serializes them.
 *   - No component needed — the framework handles it via the Accept header.
 */
class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        // CakePHP 5: No RequestHandlerComponent — use viewClasses() instead
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');

        // Force all API responses to JSON
        $this->viewBuilder()->setClassName('Json');
    }

    /**
     * CakePHP 5 content negotiation — map MIME types to View classes.
     * application/json → JsonView
     */
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        // Remove FormProtection for API (APIs use JWT/token auth, not form tokens)
        if ($this->components()->has('FormProtection')) {
            $this->components()->unload('FormProtection');
        }
    }

    /**
     * Standardized JSON success response helper.
     * Uses CakePHP 5's viewBuilder()->setOption('serialize',[...]) pattern.
     */
    protected function jsonSuccess(mixed $data = [], string $message = 'Success', int $status = 200): void
    {
        $this->response = $this->response->withStatus($status);
        $this->set('success', true);
        $this->set('message', $message);
        $this->set('data', $data);
        $this->viewBuilder()->setOption('serialize', ['success', 'message', 'data']);
    }

    /**
     * Standardized JSON error response helper.
     */
    protected function jsonError(string $message, array $errors = [], int $status = 422): void
    {
        $this->response = $this->response->withStatus($status);
        $this->set('success', false);
        $this->set('message', $message);
        $this->set('errors', $errors);
        $this->viewBuilder()->setOption('serialize', ['success', 'message', 'errors']);
    }
}
