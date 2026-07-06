<?php
$this->assign('title', 'My Profile');
?>

<div class="row g-4">
    <!-- Profile Info Card -->
    <div class="col-lg-4">
        <div class="card glass-card p-4 text-center">
            <div class="mb-3">
                <?php if (!empty($user->profile_image)): ?>
                    <img src="<?= $this->Url->build('/uploads/profiles/' . $user->profile_image) ?>" class="rounded-circle border border-3 border-primary shadow-sm" width="130" height="130" style="object-fit: cover;">
                <?php else: ?>
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto border border-3 border-primary shadow-sm" style="width: 130px; height: 130px; font-size: 3rem;">
                        <?= strtoupper(substr($user->username, 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <h4 class="fw-bold text-white mb-1"><?= h($user->username) ?></h4>
            <p class="text-muted mb-3"><?= h($user->email) ?></p>

            <div class="mb-4">
                <span class="badge bg-primary bg-opacity-20 text-primary px-3 py-2 me-1">
                    <i class="fa-solid fa-user-shield me-1"></i>
                    <?= !empty($user->user_roles) ? h($user->user_roles[0]->role->name) : 'No Role' ?>
                </span>
                <span class="badge bg-success bg-opacity-20 text-success px-3 py-2">
                    <i class="fa-solid fa-users-rectangle me-1"></i>
                    <?= !empty($user->group_users) ? h($user->group_users[0]->group->name) : 'No Group' ?>
                </span>
            </div>

            <hr class="border-secondary my-3">

            <div class="text-start">
                <div class="d-flex justify-content-between py-2 border-bottom border-secondary small">
                    <span class="text-muted">Last Login IP</span>
                    <span class="text-white fw-bold"><?= h($user->last_login_ip ?: '127.0.0.1') ?></span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom border-secondary small">
                    <span class="text-muted">Last Login Time</span>
                    <span class="text-white fw-bold"><?= $user->last_login_time ? h($user->last_login_time->format('Y-m-d H:i')) : 'Never' ?></span>
                </div>
                <div class="d-flex justify-content-between py-2 small">
                    <span class="text-muted">Registered On</span>
                    <span class="text-white fw-bold"><?= $user->created ? h($user->created->format('Y-m-d')) : 'N/A' ?></span>
                </div>
            </div>

            <div class="mt-4">
                <a href="<?= $this->Url->build(['action' => 'changePassword']) ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="fa-solid fa-key me-1"></i> Change Password
                </a>
                
                <?= $this->Form->postLink(
                    '<i class="fa-solid fa-trash-can me-1"></i> Delete Account',
                    ['action' => 'deleteAccount'],
                    [
                        'escapeTitle' => false,
                        'class' => 'btn btn-outline-danger btn-sm w-100',
                        'confirm' => __('Are you sure you want to permanently delete your account? This action cannot be undone.')
                    ]
                ) ?>
            </div>
        </div>
    </div>

    <!-- Edit Profile Card -->
    <div class="col-lg-8">
        <div class="card glass-card p-4">
            <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-user-pen text-primary me-2"></i>Edit Profile Information</h5>
            
            <?= $this->Form->create($user, ['type' => 'file', 'class' => 'needs-validation']) ?>
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

                <div class="mb-4">
                    <label for="profile_image_file" class="form-label">Upload Profile Photo</label>
                    <?= $this->Form->control('profile_image_file', [
                        'label' => false,
                        'type' => 'file',
                        'class' => 'form-control',
                        'accept' => 'image/*'
                    ]) ?>
                    <div class="form-text text-muted small mt-1">Accepts JPG, PNG, GIF. Image will be resized to 150x150px.</div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Save Changes</button>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
