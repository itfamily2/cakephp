<?php
$this->assign('title', 'Forgot Password');
?>

<div class="card glass-card p-4">
    <h3 class="fw-bold text-center text-white mb-2">Forgot Password</h3>
    <p class="text-muted text-center small mb-4">Enter your email address and we'll send you a link to reset your password.</p>
    
    <?= $this->Form->create(null, ['class' => 'needs-validation']) ?>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <?= $this->Form->control('email', [
                'label' => false,
                'type' => 'email',
                'class' => 'form-control',
                'placeholder' => 'you@example.com',
                'required' => true
            ]) ?>
        </div>

        <!-- Recaptcha Simulation -->
        <div class="mb-3 p-3 bg-dark bg-opacity-50 rounded border border-secondary d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <input type="checkbox" class="form-check-input me-3" id="recaptchaCheck" required>
                <label class="form-check-label text-white small" for="recaptchaCheck">I am not a robot</label>
            </div>
            <div class="text-center text-muted" style="font-size: 0.65rem;">
                <i class="fa-solid fa-shield-halved text-success fs-5"></i><br>
                reCAPTCHA
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3">Send Reset Link</button>
    <?= $this->Form->end() ?>

    <div class="text-center mt-2">
        <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-primary text-decoration-none small"><i class="fa-solid fa-arrow-left me-1"></i> Back to Login</a>
    </div>
</div>
