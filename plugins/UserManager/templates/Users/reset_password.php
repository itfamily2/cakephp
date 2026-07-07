<?php
$this->assign('title', 'Reset Password');
?>

<div class="card glass-card p-4">
    <h3 class="fw-bold text-center text-white mb-2">Reset Password</h3>
    <p class="text-muted text-center small mb-4">Please choose a strong password for your account.</p>
    
    <?= $this->Form->create($user, ['class' => 'needs-validation']) ?>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <?= $this->Form->control('password', [
                'label' => false,
                'type' => 'password',
                'class' => 'form-control',
                'placeholder' => '••••••••',
                'required' => true
            ]) ?>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3">Save Password</button>
    <?= $this->Form->end() ?>

    <div class="text-center mt-2">
        <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-primary text-decoration-none small"><i class="fa-solid fa-arrow-left me-1"></i> Back to Login</a>
    </div>
</div>
