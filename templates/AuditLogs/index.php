<?php $this->assign('title', 'Audit Logs'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-shield-halved text-danger me-2"></i>Audit Logs</h4>
        <p class="text-muted mb-0">Database-level record mutation history (CREATE, UPDATE, DELETE).</p>
    </div>
</div>
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Search model or action..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-2">
            <select name="action" class="form-select border-secondary bg-transparent text-white">
                <option value="">All Actions</option>
                <option value="CREATE" <?= $this->request->getQuery('action') === 'CREATE' ? 'selected' : '' ?>>CREATE</option>
                <option value="UPDATE" <?= $this->request->getQuery('action') === 'UPDATE' ? 'selected' : '' ?>>UPDATE</option>
                <option value="DELETE" <?= $this->request->getQuery('action') === 'DELETE' ? 'selected' : '' ?>>DELETE</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Filter</button>
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>
<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;">
                    <th class="ps-4 py-3">Action</th>
                    <th>Model (Table)</th>
                    <th>Record ID</th>
                    <th>Changes</th>
                    <th><?= $this->Paginator->sort('created', 'Timestamp') ?></th>
                    <th class="text-end pe-4">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($auditLogs) || !count($auditLogs)): ?>
                <tr><td colspan="6" class="text-center text-muted py-5"><i class="fa-solid fa-shield fa-2x d-block mb-2 opacity-50"></i>No audit entries found</td></tr>
                <?php else: ?>
                <?php foreach ($auditLogs as $log): ?>
                <tr>
                    <td class="ps-4">
                        <?php
                        $actionColors = ['CREATE'=>'success','UPDATE'=>'info','DELETE'=>'danger'];
                        $ac = $actionColors[$log->action ?? ''] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $ac ?> bg-opacity-20 text-<?= $ac ?> fw-bold"><?= h($log->action) ?></span>
                    </td>
                    <td><code class="text-warning small"><?= h($log->model) ?></code></td>
                    <td class="fw-bold text-muted">#<?= h($log->foreign_key) ?></td>
                    <td>
                        <?php if (!empty($log->meta)): ?>
                            <button class="btn btn-xs btn-outline-secondary btn-sm" 
                                    onclick='document.getElementById("meta-<?= $log->id ?>").classList.toggle("d-none")'>
                                <i class="fa-solid fa-code me-1"></i> View Diff
                            </button>
                            <div id="meta-<?= $log->id ?>" class="d-none mt-2">
                                <pre class="text-muted small bg-dark rounded p-2" style="max-height:100px;overflow:auto;"><?= h(json_encode(json_decode($log->meta), JSON_PRETTY_PRINT)) ?></pre>
                            </div>
                        <?php else: ?>
                            <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= $log->created ? date('M d, Y H:i:s', strtotime($log->created)) : '-' ?></td>
                    <td class="text-end pe-4">
                        <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $log->id], ['class' => 'btn btn-sm btn-outline-info', 'escape' => false]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} audit entries') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>