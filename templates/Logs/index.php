<?php
$this->assign('title', 'System Logs Management');
?>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card glass-card p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h4 class="fw-bold text-white m-0"><i class="fa-solid fa-receipt text-primary me-2"></i>System Log Files</h4>
                <p class="text-muted m-0">Manage CakePHP debug, error, and general log files.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card glass-card p-4">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Size</th>
                            <th>Last Modified</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logFiles)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No log files found in logs/ folder.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logFiles as $logFile): ?>
                                <tr>
                                    <td><span class="fw-bold text-white"><i class="fa-regular fa-file-lines text-primary me-2"></i><?= h($logFile['name']) ?></span></td>
                                    <td><code><?= number_format($logFile['size']) ?> Bytes</code></td>
                                    <td><?= h(date('Y-m-d H:i:s', $logFile['modified'])) ?></td>
                                    <td class="text-end">
                                        <a href="<?= $this->Url->build(['action' => 'view', $logFile['name']]) ?>" class="btn btn-outline-info btn-sm me-1" title="View / Edit">
                                            <i class="fa-solid fa-pencil"></i> View / Edit
                                        </a>
                                        <a href="<?= $this->Url->build(['action' => 'backup', $logFile['name']]) ?>" class="btn btn-outline-success btn-sm me-1" title="Backup">
                                            <i class="fa-solid fa-copy"></i> Backup
                                        </a>
                                        <a href="<?= $this->Url->build(['action' => 'empty', $logFile['name']]) ?>" class="btn btn-outline-warning btn-sm me-1" title="Empty" onclick="return confirm('Are you sure you want to empty the content of this log file?');">
                                            <i class="fa-solid fa-eraser"></i> Empty
                                        </a>
                                        <?= $this->Form->postLink(
                                            '<i class="fa-solid fa-trash"></i> Delete',
                                            ['action' => 'delete', $logFile['name']],
                                            [
                                                'escapeTitle' => false,
                                                'class' => 'btn btn-outline-danger btn-sm',
                                                'title' => 'Delete',
                                                'confirm' => __('Are you sure you want to delete {0}?', $logFile['name'])
                                            ]
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
