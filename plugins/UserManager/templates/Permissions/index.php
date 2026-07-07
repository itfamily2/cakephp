<?php $this->assign('title', 'Permissions'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-key text-info me-2"></i>Permissions</h4>
        <p class="text-muted mb-0">Manage granular access control rules by Controller and Action.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fa-solid fa-file-import me-2"></i>Import
        </button>
        <a href="<?= $this->Url->build(['action' => 'export']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-file-export me-2"></i>Export
        </a>
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>New Rule
        </a>
    </div>
</div>

<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Search controller or action..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-3">
            <select name="role_id" class="form-select border-secondary bg-transparent text-white">
                <option value="">All Roles</option>
                <?php foreach ($roles ?? [] as $id => $name): ?>
                    <option value="<?= h($id) ?>" <?= $this->request->getQuery('role_id') == $id ? 'selected' : '' ?>><?= h($name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Filter</button>
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="glass-card">
    <div class="table-responsive" id="permissions-table-container">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;">
                    <th class="ps-4 py-3"><?= $this->Paginator->sort('controller', 'Controller') ?></th>
                    <th><?= $this->Paginator->sort('action', 'Action') ?></th>
                    <th>Role</th>
                    <th>Group</th>
                    <th><?= $this->Paginator->sort('allowed', 'Status') ?></th>
                    <th class="text-end pe-4">Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($permissions) || !count($permissions)): ?>
                <tr><td colspan="6" class="text-center text-muted py-5"><i class="fa-solid fa-ban fa-2x d-block mb-2 opacity-50"></i>No permissions found</td></tr>
                <?php else: ?>
                <?php foreach ($permissions as $perm): ?>
                <tr>
                    <td class="ps-4 fw-bold text-info"><?= h($perm->controller) ?></td>
                    <td><code class="text-warning"><?= h($perm->action) ?></code></td>
                    <td>
                        <?= $perm->hasValue('role') ? '<span class="badge bg-primary bg-opacity-20 text-primary"><i class="fa-solid fa-user-shield me-1"></i>' . h($perm->role->name) . '</span>' : '<span class="text-muted small">—</span>' ?>
                    </td>
                    <td>
                        <?= $perm->hasValue('group') ? '<span class="badge bg-secondary bg-opacity-20 text-white"><i class="fa-solid fa-users me-1"></i>' . h($perm->group->name) . '</span>' : '<span class="text-muted small">—</span>' ?>
                    </td>
                    <td>
                        <span class="badge <?= $perm->allowed ? 'bg-success' : 'bg-danger' ?> bg-opacity-20 <?= $perm->allowed ? 'text-success' : 'text-danger' ?>">
                            <?= $perm->allowed ? '<i class="fa-solid fa-check me-1"></i>Allowed' : '<i class="fa-solid fa-xmark me-1"></i>Denied' ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $perm->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $perm->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete rule for ' . h($perm->controller) . '::' . h($perm->action) . '?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} permission rules') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <?= $this->Form->create(null, ['action' => 'bulkImport', 'type' => 'file']) ?>
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Import Permissions CSV</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Upload a CSV file with columns: ID, Controller, Action, Allowed (Yes/No).</p>
                <input type="file" name="csv_file" class="form-control border-secondary bg-transparent text-white" accept=".csv" required>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Import Data</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>