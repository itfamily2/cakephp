<?php
$this->assign('title', 'Change Password');
?>

<div class="card glass-card p-4 mx-auto" style="max-width: 600px;">
    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-key text-primary me-2"></i>Change Password</h5>
    
    <?= $this->Form->create(null, ['class' => 'needs-validation']) ?>
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <?= $this->Form->control('current_password', [
                'label' => false,
                'type' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Enter current password',
                'required' => true
            ]) ?>
        </div>
        
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <?= $this->Form->control('new_password', [
                'label' => false,
                'type' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Enter new password',
                'required' => true
            ]) ?>
        </div>

        <div class="text-end">
            <a href="<?= $this->Url->build(['action' => 'profile']) ?>" class="btn btn-outline-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Update Password</button>
        </div>
    <?= $this->Form->end() ?>
</div>
