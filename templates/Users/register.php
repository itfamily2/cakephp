<?php
$this->assign('title', 'Register');
?>

<div class="card glass-card p-4">
    <h3 class="fw-bold text-center text-white mb-4">Create Account</h3>
    
    <?= $this->Form->create($user, ['class' => 'needs-validation']) ?>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <?= $this->Form->control('username', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => 'Choose a username',
                'required' => true
            ]) ?>
        </div>

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
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <?= $this->Form->control('password', [
                'label' => false,
                'type' => 'password',
                'class' => 'form-control',
                'placeholder' => '••••••••',
                'required' => true
            ]) ?>
        </div>

        <div class="mb-3 form-check">
            <?= $this->Form->checkbox('terms', [
                'class' => 'form-check-input',
                'id' => 'termsCheck',
                'required' => true
            ]) ?>
            <label class="form-check-label text-muted small" for="termsCheck">I agree to the <a href="<?= $this->Url->build('/pages/terms') ?>" class="text-primary text-decoration-none">Terms of Service</a> & <a href="<?= $this->Url->build('/pages/privacy') ?>" class="text-primary text-decoration-none">Privacy Policy</a></label>
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

        <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3">Sign Up</button>
    <?= $this->Form->end() ?>

    <div class="text-center mt-2">
        <p class="text-muted small m-0">Already have an account? <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-primary text-decoration-none fw-bold">Sign In</a></p>
    </div>
</div>
