<?php $this->assign('title', 'Roles & Permissions'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-user-shield text-primary me-2"></i>Roles & Permissions</h4>
        <p class="text-muted mb-0">Define roles and assign granular access control permissions.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>New Role
    </a>
</div>
<div class="row g-4">
    <?php if (empty($roles) || !count($roles)): ?>
    <div class="col-12">
        <div class="glass-card p-5 text-center text-muted">
            <i class="fa-solid fa-user-shield fa-3x d-block mb-3 opacity-30"></i>
            <p>No roles defined. <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="text-primary">Create the first role</a></p>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($roles as $role): ?>
    <div class="col-md-6 col-xl-4">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3" style="background:rgba(99,102,241,0.15);">
                        <i class="fa-solid fa-shield-halved" style="color:#6366f1;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0"><?= h($role->name) ?></h6>
                        <div class="text-muted small"><?= h($role->slug ?? strtolower($role->name)) ?></div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                        <li><?= $this->Html->link('<i class="fa-solid fa-eye me-2"></i>View', ['action' => 'view', $role->id], ['class' => 'dropdown-item', 'escape' => false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa-solid fa-pen me-2"></i>Edit', ['action' => 'edit', $role->id], ['class' => 'dropdown-item', 'escape' => false]) ?></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><?= $this->Form->postLink('<i class="fa-solid fa-trash me-2"></i>Delete', ['action' => 'delete', $role->id], ['class' => 'dropdown-item text-danger', 'escape' => false, 'confirm' => 'Delete role "' . h($role->name) . '"?']) ?></li>
                    </ul>
                </div>
            </div>
            <?php if (!empty($role->description)): ?>
                <p class="text-muted small mb-3"><?= h($role->description) ?></p>
            <?php endif; ?>
            <div class="d-flex align-items-center justify-content-between mt-auto pt-3" style="border-top:1px solid var(--border-color);">
                <span class="text-muted small"><i class="fa-solid fa-users me-1"></i><?= count($role->user_roles ?? []) ?> users</span>
                <span class="text-muted small"><i class="fa-solid fa-key me-1"></i><?= count($role->permissions ?? []) ?> permissions</span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>