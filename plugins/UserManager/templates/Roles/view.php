<?php $this->assign('title', 'View Role'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-user-shield text-primary me-2"></i><?= h($role->name) ?> Role</h4>
        <p class="text-muted mb-0">Detailed view and permission mapping.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $role->id]) ?>" class="btn btn-primary">
            <i class="fa-solid fa-pen me-2"></i>Edit Role
        </a>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary bg-white">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Roles
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Role Information -->
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-circle-info text-info me-2"></i>Role Information</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <tr>
                            <th class="text-muted w-35 ps-4 py-3 border-0 border-bottom">Name</th>
                            <td class="fw-bold text-dark py-3 border-0 border-bottom"><?= h($role->name) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted ps-4 py-3 border-0 border-bottom">Slug</th>
                            <td class="py-3 border-0 border-bottom"><span class="badge bg-light border text-dark font-monospace"><?= h($role->slug ?? strtolower($role->name)) ?></span></td>
                        </tr>
                        <tr>
                            <th class="text-muted ps-4 py-3 border-0 border-bottom">Description</th>
                            <td class="py-3 border-0 border-bottom text-dark"><?= h($role->description ?? 'No description provided.') ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted ps-4 py-3 border-0">Created</th>
                            <td class="py-3 border-0 text-dark"><?= $role->created ? $role->created->format('M d, Y H:i') : '-' ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Assigned Users -->
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-users text-primary me-2"></i>Assigned Users</h6>
                <span class="badge bg-primary rounded-pill"><?= count($role->user_roles ?? []) ?> Users</span>
            </div>
            <div class="card-body p-4">
                <?php if (empty($role->user_roles)): ?>
                    <div class="text-center text-muted py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fa-solid fa-user-xmark fs-4"></i>
                        </div>
                        <p class="mb-0 fw-medium">No users assigned to this role.</p>
                        <small>Users can be assigned from the User Management page.</small>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($role->user_roles as $ur): ?>
                            <div class="d-inline-flex align-items-center bg-light border rounded-pill px-3 py-2">
                                <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                    <i class="fa-solid fa-user text-white" style="font-size: 10px;"></i>
                                </div>
                                <span class="fw-medium text-dark"><?= h($ur->user->username ?? 'User #' . $ur->user_id) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Role Permissions -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-key text-warning me-2"></i>Role Permissions</h6>
        <a href="<?= $this->Url->build(['controller' => 'Permissions', 'action' => 'index', '?' => ['role_id' => $role->id]]) ?>" class="btn btn-sm btn-outline-primary">
            <i class="fa-solid fa-sliders me-1"></i> Manage Permissions
        </a>
    </div>
    <div class="card-body p-4">
        <?php if (empty($role->permissions)): ?>
            <div class="text-center text-muted py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-shield-halved fs-3 text-secondary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">No Specific Permissions</h6>
                <p class="mb-0">This role relies entirely on default application access rules.</p>
            </div>
        <?php else: ?>
            <?php 
            // Group permissions by controller for better display
            $groupedPerms = [];
            foreach ($role->permissions as $p) {
                $groupedPerms[$p->controller][] = $p;
            }
            ?>
            <div class="row g-4">
                <?php foreach ($groupedPerms as $controller => $perms): ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="card border h-100 shadow-none">
                            <div class="card-header bg-light border-bottom py-2">
                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-cube text-secondary me-2"></i><?= h($controller) ?></h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($perms as $p): ?>
                                        <?php if ($p->allowed): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                <i class="fa-solid fa-check me-1"></i><?= h($p->action) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                                <i class="fa-solid fa-xmark me-1"></i><?= h($p->action) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>