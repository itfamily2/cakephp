<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Phase 16 - API Users Controller
 * Handles JWT token generation (Authentication).
 */
class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['token']);
        $this->Authorization->skipAuthorization();
    }

    /**
     * POST /api/v1/users/token
     * Authenticates user and returns a JWT
     */
    public function token()
    {
        $this->request->allowMethod(['post']);
        
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $user = $result->getData();
            
            // Generate JWT Token
            $privateKey = Security::getSalt();
            $payload = [
                'sub' => $user->id,
                'exp' => time() + (3600 * 24), // Expire in 1 day
                'iat' => time()
            ];
            
            $token = JWT::encode($payload, $privateKey, 'HS256');
            
            $this->set([
                'success' => true,
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email
                    ]
                ],
                '_serialize' => ['success', 'data']
            ]);
        } else {
            $this->response = $this->response->withStatus(401);
            $this->set([
                'success' => false,
                'message' => 'Invalid credentials',
                '_serialize' => ['success', 'message']
            ]);
        }
    }
}
