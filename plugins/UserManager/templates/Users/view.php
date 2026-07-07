<?php $this->assign('title', 'View User Profile'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-user text-primary me-2"></i><?= h($user->username) ?></h4>
        <p class="text-muted mb-0">System User Profile & Configuration</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $user->id]) ?>" class="btn btn-outline-primary">
            <i class="fa-solid fa-pen me-2"></i>Edit
        </a>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="glass-card p-4 text-center h-100 d-flex flex-column justify-content-center">
            <div class="rounded-circle bg-primary bg-opacity-20 d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;">
                <span class="fs-1 fw-bold text-primary"><?= strtoupper(substr($user->username, 0, 1)) ?></span>
            </div>
            <h5 class="fw-bold mb-1"><?= h($user->username) ?></h5>
            <p class="text-muted small mb-2"><?= h($user->email) ?></p>
            <div>
                <?php if ($user->is_active): ?>
                    <span class="badge bg-success bg-opacity-20 text-success"><i class="fa-solid fa-check me-1"></i>Active</span>
                <?php else: ?>
                    <span class="badge bg-danger bg-opacity-20 text-danger"><i class="fa-solid fa-ban me-1"></i>Suspended</span>
                <?php endif; ?>
                <?php if ($user->email_verified): ?>
                    <span class="badge bg-info bg-opacity-20 text-info"><i class="fa-solid fa-envelope-circle-check me-1"></i>Verified</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="glass-card p-4 h-100">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="border-color:var(--border-color)!important;">Account Details</h6>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="text-muted small">Registered</label>
                    <div class="fw-semibold"><?= $user->created ? $user->created->format('M d, Y H:i') : '-' ?></div>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small">Last Login</label>
                    <div class="fw-semibold text-info"><?= $user->last_login_time ? $user->last_login_time->format('M d, Y H:i') : 'Never' ?></div>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small">Last IP Address</label>
                    <div class="fw-semibold font-monospace"><?= h($user->last_login_ip ?? 'Unknown') ?></div>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small">Phone Number</label>
                    <div class="fw-semibold"><?= !empty($user->phone) ? h($user->phone) : '—' ?></div>
                </div>
            </div>
            
            <h6 class="fw-bold mb-3 border-bottom pb-2 mt-4" style="border-color:var(--border-color)!important;">Access Control</h6>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="text-muted small d-block mb-1">Assigned Role</label>
                    <?php if (!empty($user->user_roles)): ?>
                        <?php foreach ($user->user_roles as $ur): ?>
                            <span class="badge bg-primary bg-opacity-20 text-primary p-2">
                                <i class="fa-solid fa-user-shield me-1"></i><?= h($ur->role->name ?? 'Role #' . $ur->role_id) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted small">No role assigned</span>
                    <?php endif; ?>
                </div>
                <div class="col-sm-6">
                    <label class="text-muted small d-block mb-1">Assigned Group</label>
                    <?php if (!empty($user->group_users)): ?>
                        <?php foreach ($user->group_users as $gu): ?>
                            <span class="badge bg-secondary bg-opacity-20 text-white p-2">
                                <i class="fa-solid fa-users me-1"></i><?= h($gu->group->name ?? 'Group #' . $gu->group_id) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted small">No group assigned</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="glass-card p-4">
    <h6 class="fw-bold mb-3 border-bottom pb-2" style="border-color:var(--border-color)!important;">Effective Permissions</h6>
    <?php if (empty($permissions)): ?>
        <div class="text-center text-muted py-4">
            <i class="fa-solid fa-key fa-2x mb-2 opacity-50"></i>
            <p class="mb-0">This user has no specific permissions assigned.</p>
        </div>
    <?php else: ?>
        <?php 
        $groupedPerms = [];
        foreach ($permissions as $p) {
            $groupedPerms[$p->controller][] = $p;
        }
        ?>
        <div class="row g-3">
            <?php foreach ($groupedPerms as $controller => $perms): ?>
                <div class="col-md-4 col-xl-3">
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
