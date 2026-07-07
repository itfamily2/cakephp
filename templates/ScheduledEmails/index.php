<?php $this->assign('title', 'Scheduled Emails'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-clock text-primary me-2"></i>Scheduled Emails</h4>
        <p class="text-muted mb-0">Manage emails queued to be sent at a scheduled time.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Schedule Email
    </a>
</div>
<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;">
                    <th class="ps-4 py-3">Recipient</th>
                    <th>Subject</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th><?= $this->Paginator->sort('scheduled_at', 'Scheduled For') ?></th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($scheduledEmails) || !count($scheduledEmails)): ?>
                <tr><td colspan="6" class="text-center text-muted py-5"><i class="fa-solid fa-envelope-open fa-2x d-block mb-2 opacity-50"></i>No scheduled emails</td></tr>
                <?php else: ?>
                <?php foreach ($scheduledEmails as $email): ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-semibold"><?= h($email->to_name ?? '') ?></div>
                        <div class="text-muted small"><?= h($email->to_email) ?></div>
                    </td>
                    <td><?= h($email->subject) ?></td>
                    <td><span class="badge bg-secondary bg-opacity-30 text-muted small"><?= h($email->template ?? 'default') ?></span></td>
                    <td>
                        <?php
                        $sc = ['pending'=>'warning','sent'=>'success','failed'=>'danger','cancelled'=>'secondary'][$email->status ?? 'pending'] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $sc ?> bg-opacity-20 text-<?= $sc ?>"><?= ucfirst($email->status ?? 'pending') ?></span>
                    </td>
                    <td class="text-muted small"><?= !empty($email->scheduled_at) ? date('M d, Y H:i', strtotime($email->scheduled_at)) : '-' ?></td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $email->id], ['class' => 'btn btn-sm btn-outline-info', 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $email->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $email->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete this scheduled email?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} scheduled emails') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>