<?php $this->assign('title', 'View Role'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-user-shield text-primary me-2"></i><?= h($role->name) ?> Role</h4>
        <p class="text-muted mb-0">Detailed view and permission mapping.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $role->id]) ?>" class="btn btn-outline-primary">
            <i class="fa-solid fa-pen me-2"></i>Edit Role
        </a>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Roles
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-5">
        <div class="glass-card p-4 h-100">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="border-color:var(--border-color)!important;">Role Information</h6>
            <table class="table table-dark table-borderless mb-0">
                <tr>
                    <th class="text-muted w-25">Name</th>
                    <td class="fw-bold"><?= h($role->name) ?></td>
                </tr>
                <tr>
                    <th class="text-muted">Slug</th>
                    <td><code class="text-info"><?= h($role->slug ?? strtolower($role->name)) ?></code></td>
                </tr>
                <tr>
                    <th class="text-muted">Description</th>
                    <td><?= h($role->description ?? 'No description provided.') ?></td>
                </tr>
                <tr>
                    <th class="text-muted">Created</th>
                    <td><?= $role->created ? $role->created->format('M d, Y H:i') : '-' ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-7">
        <div class="glass-card p-4 h-100">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="border-color:var(--border-color)!important;">Assigned Users (<?= count($role->user_roles ?? []) ?>)</h6>
            <?php if (empty($role->user_roles)): ?>
                <div class="text-center text-muted py-4">
                    <i class="fa-solid fa-users fa-2x mb-2 opacity-50"></i>
                    <p class="mb-0">No users assigned to this role.</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($role->user_roles as $ur): ?>
                        <span class="badge bg-secondary bg-opacity-20 text-white p-2">
                            <i class="fa-solid fa-user me-1 text-primary"></i>
                            <?= h($ur->user->username ?? 'User #' . $ur->user_id) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" style="border-color:var(--border-color)!important;">
        <h6 class="fw-bold mb-0">Role Permissions</h6>
        <a href="<?= $this->Url->build(['controller' => 'Permissions', 'action' => 'index', '?' => ['role_id' => $role->id]]) ?>" class="btn btn-sm btn-outline-info">
            Manage Permissions
        </a>
    </div>
    
    <?php if (empty($role->permissions)): ?>
        <div class="text-center text-muted py-5">
            <i class="fa-solid fa-key fa-3x mb-3 opacity-30 d-block"></i>
            <p>This role has no specific permissions assigned. It will rely on default access rules.</p>
        </div>
    <?php else: ?>
        <?php 
        // Group permissions by controller for better display
        $groupedPerms = [];
        foreach ($role->permissions as $p) {
            $groupedPerms[$p->controller][] = $p;
        }
        ?>
        <div class="row g-3">
            <?php foreach ($groupedPerms as $controller => $perms): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="p-3 rounded" style="background:rgba(0,0,0,0.2); border:1px solid var(--border-color);">
                        <h6 class="fw-bold text-info mb-2"><i class="fa-solid fa-cube me-2"></i><?= h($controller) ?></h6>
                        <div class="d-flex flex-wrap gap-1">
                            <?php foreach ($perms as $p): ?>
                                <span class="badge <?= $p->allowed ? 'bg-success' : 'bg-danger' ?> bg-opacity-20 <?= $p->allowed ? 'text-success' : 'text-danger' ?>">
                                    <?= h($p->action) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>