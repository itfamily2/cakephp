<?php
$this->assign('title', 'Login');
?>

<div class="card glass-card p-4">
    <h3 class="fw-bold text-center text-white mb-4">Account Login</h3>
    
    <?= $this->Form->create(null, ['class' => 'needs-validation']) ?>
        <div class="mb-3">
            <label for="username" class="form-label">Username or Email</label>
            <?= $this->Form->control('username', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => 'Enter username or email',
                'required' => true
            ]) ?>
        </div>
        
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label">Password</label>
                <a href="<?= $this->Url->build(['action' => 'forgotPassword']) ?>" class="text-primary text-decoration-none small">Forgot?</a>
            </div>
            <?= $this->Form->control('password', [
                'label' => false,
                'type' => 'password',
                'class' => 'form-control',
                'placeholder' => '••••••••',
                'required' => true
            ]) ?>
        </div>

        <div class="mb-3 form-check d-flex justify-content-between align-items-center">
            <div>
                <?= $this->Form->checkbox('remember_me', [
                    'class' => 'form-check-input',
                    'id' => 'rememberMe'
                ]) ?>
                <label class="form-check-label text-muted small" for="rememberMe">Remember me</label>
            </div>
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

        <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3">Sign In</button>
    <?= $this->Form->end() ?>

    <div class="text-center text-muted my-2 small">— OR SIGN IN WITH —</div>

    <div class="row g-2 mb-4">
        <div class="col-6">
            <a href="<?= $this->Url->build(['action' => 'socialLogin', 'google']) ?>" class="btn btn-outline-light btn-sm w-100 py-2 d-flex align-items-center justify-content-center">
                <i class="fa-brands fa-google text-danger me-2"></i> Google
            </a>
        </div>
        <div class="col-6">
            <a href="<?= $this->Url->build(['action' => 'socialLogin', 'facebook']) ?>" class="btn btn-outline-light btn-sm w-100 py-2 d-flex align-items-center justify-content-center">
                <i class="fa-brands fa-facebook text-info me-2"></i> Facebook
            </a>
        </div>
        <div class="col-6">
            <a href="<?= $this->Url->build(['action' => 'socialLogin', 'linkedin']) ?>" class="btn btn-outline-light btn-sm w-100 py-2 d-flex align-items-center justify-content-center">
                <i class="fa-brands fa-linkedin text-primary me-2"></i> LinkedIn
            </a>
        </div>
        <div class="col-6">
            <a href="<?= $this->Url->build(['action' => 'socialLogin', 'twitter']) ?>" class="btn btn-outline-light btn-sm w-100 py-2 d-flex align-items-center justify-content-center">
                <i class="fa-brands fa-x-twitter text-white me-2"></i> Twitter
            </a>
        </div>
    </div>

    <div class="text-center mt-2">
        <p class="text-muted small m-0">Don't have an account? <a href="<?= $this->Url->build(['action' => 'register']) ?>" class="text-primary text-decoration-none fw-bold">Sign Up</a></p>
    </div>
</div>
