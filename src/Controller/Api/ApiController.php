<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Base API Controller
 */
class ApiController extends AppController
{
    /**
     * Initialize controller
     */
    public function initialize(): void
    {
        parent::initialize();
        
        // Disable FormProtection for API endpoints by unloading it
        if ($this->components()->has('FormProtection')) {
            $this->components()->unload('FormProtection');
        }

        // Set response type to JSON by default
        $this->viewBuilder()->setClassName('Json');
    }

    /**
     * beforeFilter event
     */
    public function beforeFilter(EventInterface $event)
    {
        // 1. Attempt JWT Authentication FIRST
        $this->authenticateJwt();

        $action = strtolower($this->request->getParam('action') ?? '');
        $publicActions = ['login', 'register', 'forgotpassword', 'resetpassword', 'verifyemail'];

        // 2. If it's a public action, skip authorization and call parent
        if (in_array($action, $publicActions)) {
            $this->Authorization->skipAuthorization();
            return parent::beforeFilter($event);
        }

        // 3. For protected actions, ensure we have an authenticated user identity
        if ($this->Authentication->getIdentity() === null) {
            return $this->jsonError('Unauthorized. Please provide a valid Bearer token.', 401);
        }

        // 4. Run parent beforeFilter inside try-catch to handle authorization failures
        try {
            return parent::beforeFilter($event);
        } catch (\Authorization\Exception\ForbiddenException $e) {
            return $this->jsonError('Forbidden. You do not have permission to access this resource.', 403);
        }
    }

    /**
     * Parse and authenticate JWT token
     */
    protected function authenticateJwt(): void
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            return; // No JWT token present
        }

        $token = substr($authHeader, 7);
        $secret = Configure::read('Security.jwt_secret');

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            
            // Check if user ID is in payload
            if (isset($decoded->sub)) {
                $usersTable = TableRegistry::getTableLocator()->get('Users');
                $user = $usersTable->find()
                    ->where(['id' => $decoded->sub, 'is_active' => true])
                    ->first();

                if ($user) {
                    // Wrap identity to satisfy both Authentication and Authorization plugins
                    $identity = new \Authentication\Identity($user);
                    $authService = $this->request->getAttribute('authorization');
                    if ($authService) {
                        $identity = new \Authorization\Identity($authService, $identity);
                    }
                    // Store decorated identity directly in the request attributes
                    $this->request = $this->request->withAttribute('identity', $identity);
                }
            }
        } catch (\Exception $e) {
            // Token is invalid or expired
            // We don't fail immediately here in case the action is a public action
            // Instead, we let the normal auth check fail if required
        }
    }

    /**
     * Standard JSON success responder
     */
    protected function jsonSuccess(array $data, string $message = 'Success', int $code = 200)
    {
        return $this->response->withType('application/json')
            ->withStatus($code)
            ->withStringBody(json_encode([
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ]));
    }

    /**
     * Standard JSON error responder
     */
    protected function jsonError(string $message, int $code = 400, array $errors = [])
    {
        $payload = [
            'status' => 'error',
            'message' => $message
        ];
        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        return $this->response->withType('application/json')
            ->withStatus($code)
            ->withStringBody(json_encode($payload));
    }
}
