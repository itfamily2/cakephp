<?php $this->assign('title', 'Activity Logs'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-clock-rotate-left text-info me-2"></i>Activity Logs</h4>
        <p class="text-muted mb-0">Full audit trail of all user actions across the system.</p>
    </div>
</div>
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Search action or description..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-2">
            <input type="date" name="from" class="form-control border-secondary bg-transparent text-white" value="<?= h($this->request->getQuery('from')) ?>">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control border-secondary bg-transparent text-white" value="<?= h($this->request->getQuery('to')) ?>">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Filter</button>
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">Reset</a>
            <a href="<?= $this->Url->build(['action' => 'index', '?' => ['_ext' => 'csv'] + $this->request->getQueryParams()]) ?>" class="btn btn-outline-success">
                <i class="fa-solid fa-file-csv"></i> Export
            </a>
        </div>
    </form>
</div>
<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;">
                    <th class="ps-4 py-3">User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th><?= $this->Paginator->sort('created', 'Time') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($activityLogs) || !count($activityLogs)): ?>
                <tr><td colspan="5" class="text-center text-muted py-5"><i class="fa-solid fa-inbox fa-2x d-block mb-2 opacity-50"></i>No activity recorded</td></tr>
                <?php else: ?>
                <?php foreach ($activityLogs as $log): ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                 style="width:32px;height:32px;background:rgba(99,102,241,0.2);color:#6366f1;font-size:0.8rem;">
                                <?= strtoupper(substr($log->user->username ?? 'S', 0, 1)) ?>
                            </div>
                            <span class="fw-semibold"><?= h($log->user->username ?? 'System') ?></span>
                        </div>
                    </td>
                    <td><span class="badge bg-primary bg-opacity-20 text-primary"><?= h($log->action) ?></span></td>
                    <td class="text-muted"><?= h($log->description) ?></td>
                    <td><code class="text-muted small"><?= h($log->ip_address ?? '—') ?></code></td>
                    <td class="text-muted small"><?= $log->created ? date('M d, Y H:i:s', strtotime($log->created)) : '-' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} log entries total') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>