<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Phase 19 - CaptchaComponent
 * 
 * Generates simple math-based CAPTCHA challenges and validates responses.
 * For production, swap this implementation for Google reCAPTCHA v3 by 
 * calling Google's verify API endpoint instead of session comparison.
 */
class CaptchaComponent extends Component
{
    protected array $_defaultConfig = [
        'sessionKey' => 'Security.captcha_answer',
    ];

    /**
     * Generate a new CAPTCHA challenge and store the answer in the session.
     * Returns the question string to display in the view.
     *
     * Usage in Controller:
     *   $captchaQuestion = $this->Captcha->generate();
     *   $this->set('captchaQuestion', $captchaQuestion);
     */
    public function generate(): string
    {
        $a = rand(2, 15);
        $b = rand(1, 10);
        $answer = $a + $b;

        // Store the correct answer in the user's server-side session
        $this->getController()
             ->getRequest()
             ->getSession()
             ->write($this->getConfig('sessionKey'), $answer);

        return "What is {$a} + {$b}?";
    }

    /**
     * Validate the user's CAPTCHA response against the stored session answer.
     * Clears the session answer after checking (one-time use).
     *
     * Usage in Controller:
     *   if (!$this->Captcha->validate($this->request->getData('captcha'))) {
     *       // fail
     *   }
     */
    public function validate(?string $userAnswer): bool
    {
        $session = $this->getController()->getRequest()->getSession();
        $correctAnswer = $session->read($this->getConfig('sessionKey'));
        
        // One-time use: always delete after checking
        $session->delete($this->getConfig('sessionKey'));

        if ($correctAnswer === null) {
            Log::warning('[Security] CAPTCHA session expired or missing.');
            return false;
        }

        $isValid = ((int)$userAnswer === (int)$correctAnswer);
        
        if (!$isValid) {
            Log::warning('[Security] CAPTCHA validation failed.');
        }

        return $isValid;
    }
}
