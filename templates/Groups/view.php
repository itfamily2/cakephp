<?php $this->assign('title', 'View Group: ' . h($group->name)); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>">Groups</a></li>
                <li class="breadcrumb-item active"><?= h($group->name) ?></li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0"><i class="fa-solid fa-users-rectangle text-primary me-2"></i><?= h($group->name) ?></h4>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $group->id]) ?>" class="btn btn-primary">
            <i class="fa-solid fa-pen me-2"></i>Edit Group
        </a>
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-1"></i>Delete',
            ['action' => 'delete', $group->id],
            ['confirm' => 'Delete this group and all its members?', 'class' => 'btn btn-outline-danger', 'escape' => false]
        ) ?>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Group Info -->
    <div class="col-lg-4">
        <div class="glass-card p-4 mb-4">
            <div class="text-center mb-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;background:var(--primary-light);">
                    <i class="fa-solid fa-users-rectangle fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold mb-1"><?= h($group->name) ?></h5>
                <?php if (!empty($group->parent_group)): ?>
                    <span class="badge bg-secondary text-muted">
                        <i class="fa-solid fa-sitemap me-1"></i>Under: <?= h($group->parent_group->name) ?>
                    </span>
                <?php else: ?>
                    <span class="badge bg-primary">
                        <i class="fa-solid fa-crown me-1"></i>Top-Level Group
                    </span>
                <?php endif; ?>
            </div>
            <hr>
            <div class="row g-2 text-center">
                <div class="col-4">
                    <div class="fw-bold fs-5 text-primary"><?= count($group->group_users ?? []) ?></div>
                    <div class="text-muted" style="font-size:0.75rem;">Members</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold fs-5 text-success"><?= count($group->child_groups ?? []) ?></div>
                    <div class="text-muted" style="font-size:0.75rem;">Sub-Groups</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold fs-5 text-warning"><?= count($group->permissions ?? []) ?></div>
                    <div class="text-muted" style="font-size:0.75rem;">Permissions</div>
                </div>
            </div>
            <hr>
            <div class="small">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Created</span>
                    <span class="fw-semibold"><?= $group->created ? $group->created->format('M d, Y') : '—' ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Modified</span>
                    <span class="fw-semibold"><?= $group->modified ? $group->modified->format('M d, Y') : '—' ?></span>
                </div>
            </div>
        </div>

        <!-- Sub-Groups -->
        <?php if (!empty($group->child_groups)): ?>
        <div class="glass-card p-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-sitemap me-2 text-primary"></i>Sub-Groups</h6>
            <?php foreach ($group->child_groups as $child): ?>
                <a href="<?= $this->Url->build(['action' => 'view', $child->id]) ?>"
                   class="d-flex align-items-center gap-2 p-2 rounded mb-1 text-decoration-none"
                   style="background:var(--bg-surface-2);border:1px solid var(--border-light);">
                    <i class="fa-solid fa-users text-primary"></i>
                    <span class="fw-semibold text-dark small"><?= h($child->name) ?></span>
                    <i class="fa-solid fa-angle-right ms-auto text-muted"></i>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Members + Permissions -->
    <div class="col-lg-8">
        <!-- Members -->
        <div class="glass-card mb-4">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="border-color:var(--border-color);">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-user-group me-2 text-primary"></i>Members (<?= count($group->group_users ?? []) ?>)</h6>
            </div>
            <?php if (empty($group->group_users)): ?>
                <div class="text-center text-muted py-4 small">
                    <i class="fa-solid fa-users-slash fa-2x mb-2 d-block opacity-50"></i>
                    No members in this group.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">User ID</th>
                                <th>Joined</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group->group_users as $gu): ?>
                            <tr>
                                <td class="ps-4">
                                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'view', $gu->user_id]) ?>"
                                       class="fw-semibold text-decoration-none text-primary">
                                        <i class="fa-regular fa-circle-user me-2"></i>User #<?= $gu->user_id ?>
                                    </a>
                                </td>
                                <td class="text-muted small"><?= isset($gu->created) ? $gu->created->format('M d, Y') : '—' ?></td>
                                <td class="text-end pe-4">
                                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'view', $gu->user_id]) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Permissions -->
        <div class="glass-card">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="border-color:var(--border-color);">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-key me-2 text-warning"></i>Group Permissions (<?= count($group->permissions ?? []) ?>)</h6>
                <a href="<?= $this->Url->build(['controller' => 'Permissions', 'action' => 'add']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-plus me-1"></i>Add Rule
                </a>
            </div>
            <?php if (empty($group->permissions)): ?>
                <div class="text-center text-muted py-4 small">
                    <i class="fa-solid fa-key fa-2x mb-2 d-block opacity-50"></i>
                    No permissions assigned to this group.
                </div>
            <?php else: ?>
                <?php
                $groupedPerms = [];
                foreach ($group->permissions as $p) { $groupedPerms[$p->controller][] = $p; }
                ?>
                <div class="p-4">
                    <div class="row g-3">
                        <?php foreach ($groupedPerms as $ctrl => $perms): ?>
                        <div class="col-md-4">
                            <div class="p-3 rounded" style="border:1px solid var(--border-color);background:var(--bg-surface-2);">
                                <div class="fw-semibold text-primary small mb-2"><i class="fa-solid fa-cube me-1"></i><?= h($ctrl) ?></div>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($perms as $p): ?>
                                        <span class="badge <?= $p->allowed ? 'bg-success text-success' : 'bg-danger text-danger' ?>" style="font-size:0.7rem;">
                                            <?= h($p->action) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>