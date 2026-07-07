<?php $this->assign('title', 'Log Files'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-receipt text-warning me-2"></i>System Log Files</h4>
        <p class="text-muted mb-0">View and manage application log files stored in <code class="text-warning"><?= h(LOGS) ?></code></p>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($logFiles)): ?>
    <div class="col-12">
        <div class="glass-card p-5 text-center text-muted">
            <i class="fa-solid fa-file-circle-xmark fa-3x d-block mb-3 opacity-30"></i>
            <p>No log files found in the logs directory.</p>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($logFiles as $log): ?>
    <div class="col-md-6 col-xl-4">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 flex-shrink-0" style="background:rgba(245,158,11,0.15);">
                        <i class="fa-solid fa-file-lines" style="color:#f59e0b;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0"><?= h($log['name']) ?></h6>
                        <div class="text-muted small"><?= number_format($log['size'] / 1024, 1) ?> KB &bull; <?= number_format($log['lines']) ?> lines</div>
                    </div>
                </div>
            </div>
            <div class="text-muted small mb-4">
                <i class="fa-solid fa-clock me-1"></i>Modified: <?= date('M d, Y H:i', $log['modified']) ?>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <?= $this->Html->link('<i class="fa-solid fa-eye me-1"></i>View', ['action' => 'view', $log['name']], ['class' => 'btn btn-sm btn-outline-info flex-fill', 'escape' => false]) ?>
                <?= $this->Form->postLink('<i class="fa-solid fa-broom me-1"></i>Clear', ['action' => 'empty', $log['name']], ['class' => 'btn btn-sm btn-outline-warning flex-fill', 'escape' => false, 'confirm' => 'Clear all content from "' . h($log['name']) . '"?']) ?>
                <?= $this->Form->postLink('<i class="fa-solid fa-floppy-disk me-1"></i>Backup', ['action' => 'backup', $log['name']], ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false]) ?>
                <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $log['name']], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Permanently delete "' . h($log['name']) . '"?']) ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
