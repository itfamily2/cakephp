<?php
$this->assign('title', 'Edit User');
?>

<div class="card glass-card p-4 mx-auto" style="max-width: 700px;">
    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-user-pen text-primary me-2"></i>Edit User Account</h5>
    
    <?= $this->Form->create($user, ['class' => 'needs-validation']) ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username</label>
                <?= $this->Form->control('username', [
                    'label' => false,
                    'class' => 'form-control',
                    'required' => true
                ]) ?>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email Address</label>
                <?= $this->Form->control('email', [
                    'label' => false,
                    'type' => 'email',
                    'class' => 'form-control',
                    'required' => true
                ]) ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (Leave blank to keep current)</label>
            <?= $this->Form->control('password', [
                'label' => false,
                'type' => 'password',
                'class' => 'form-control',
                'placeholder' => '••••••••',
                'required' => false,
                'value' => ''
            ]) ?>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="role-id" class="form-label">User Role</label>
                <?= $this->Form->control('role_id', [
                    'label' => false,
                    'type' => 'select',
                    'options' => $roles,
                    'value' => $currentRoleId,
                    'empty' => 'Select Role',
                    'class' => 'form-select',
                    'required' => true
                ]) ?>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="group-id" class="form-label">Primary Group</label>
                <?= $this->Form->control('group_id', [
                    'label' => false,
                    'type' => 'select',
                    'options' => $groups,
                    'value' => $currentGroupId,
                    'empty' => 'Select Group',
                    'class' => 'form-select',
                    'required' => true
                ]) ?>
            </div>
        </div>

        <div class="mb-3 form-check">
            <?= $this->Form->checkbox('is_active', [
                'class' => 'form-check-input',
                'id' => 'isActiveCheck'
            ]) ?>
            <label class="form-check-label text-muted small" for="isActiveCheck">Account is Active</label>
        </div>

        <div class="text-end mt-4">
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Save Changes</button>
        </div>
    <?= $this->Form->end() ?>
</div>
