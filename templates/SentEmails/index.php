<?php $this->assign('title', 'Sent Emails'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-paper-plane text-success me-2"></i>Sent Emails</h4>
        <p class="text-muted mb-0">History of all outbound emails sent from the system.</p>
    </div>
</div>
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Search recipient or subject..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Search</button>
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>
<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;">
                    <th class="ps-4 py-3">To</th>
                    <th>Subject</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th><?= $this->Paginator->sort('sent_at', 'Sent At') ?></th>
                    <th class="text-end pe-4">View</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sentEmails) || !count($sentEmails)): ?>
                <tr><td colspan="6" class="text-center text-muted py-5"><i class="fa-solid fa-paper-plane fa-2x d-block mb-2 opacity-50"></i>No sent emails</td></tr>
                <?php else: ?>
                <?php foreach ($sentEmails as $email): ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-semibold"><?= h($email->to_name ?? '') ?></div>
                        <div class="text-muted small"><?= h($email->to_email) ?></div>
                    </td>
                    <td><?= h($email->subject) ?></td>
                    <td><span class="badge bg-secondary bg-opacity-30 text-muted small"><?= h($email->template ?? 'default') ?></span></td>
                    <td>
                        <?php $delivered = $email->status === 'delivered' || $email->status === 'sent'; ?>
                        <span class="badge <?= $delivered ? 'bg-success' : 'bg-danger' ?> bg-opacity-20 <?= $delivered ? 'text-success' : 'text-danger' ?>">
                            <i class="fa-solid fa-<?= $delivered ? 'check' : 'xmark' ?> me-1"></i><?= ucfirst($email->status ?? 'sent') ?>
                        </span>
                    </td>
                    <td class="text-muted small"><?= !empty($email->sent_at) ? date('M d, Y H:i', strtotime($email->sent_at)) : '-' ?></td>
                    <td class="text-end pe-4">
                        <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $email->id], ['class' => 'btn btn-sm btn-outline-info ajax-modal-link', 'escape' => false]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} emails sent') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>