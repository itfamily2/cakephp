<?php $this->assign('title', 'Contact Enquiries'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-envelope-open-text text-danger me-2"></i>Contact Enquiries</h4>
        <p class="text-muted mb-0">Manage inbound contact form submissions and customer queries.</p>
    </div>
</div>
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Search by name, email or subject..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-2">
            <select name="replied" class="form-select border-secondary bg-transparent text-white">
                <option value="">All</option>
                <option value="0" <?= $this->request->getQuery('replied') === '0' ? 'selected' : '' ?>>Pending Reply</option>
                <option value="1" <?= $this->request->getQuery('replied') === '1' ? 'selected' : '' ?>>Replied</option>
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
                    <th class="ps-4 py-3">Sender</th>
                    <th>Subject</th>
                    <th>Message Preview</th>
                    <th>Status</th>
                    <th><?= $this->Paginator->sort('created', 'Received') ?></th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contactEnquiries) || !count($contactEnquiries)): ?>
                <tr><td colspan="6" class="text-center text-muted py-5"><i class="fa-solid fa-inbox fa-2x d-block mb-2 opacity-50"></i>No enquiries yet</td></tr>
                <?php else: ?>
                <?php foreach ($contactEnquiries as $enquiry): ?>
                <tr class="<?= !($enquiry->replied ?? false) ? 'table-active' : '' ?>">
                    <td class="ps-4">
                        <div class="fw-semibold"><?= h($enquiry->name) ?></div>
                        <div class="text-muted small"><?= h($enquiry->email) ?></div>
                        <?php if (!empty($enquiry->phone)): ?>
                            <div class="text-muted small"><i class="fa-solid fa-phone me-1"></i><?= h($enquiry->phone) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-semibold"><?= h($enquiry->subject ?? '(No Subject)') ?></td>
                    <td class="text-muted small" style="max-width:250px;">
                        <?= h(mb_substr(strip_tags($enquiry->message ?? ''), 0, 80)) ?>...
                    </td>
                    <td>
                        <?php $replied = $enquiry->replied ?? false; ?>
                        <span class="badge <?= $replied ? 'bg-success' : 'bg-warning' ?> bg-opacity-20 <?= $replied ? 'text-success' : 'text-warning' ?>">
                            <?= $replied ? '<i class="fa-solid fa-check me-1"></i>Replied' : '<i class="fa-solid fa-clock me-1"></i>Pending' ?>
                        </span>
                    </td>
                    <td class="text-muted small"><?= $enquiry->created ? date('M d, Y H:i', strtotime($enquiry->created)) : '-' ?></td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $enquiry->id], ['class' => 'btn btn-sm btn-outline-info', 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa-solid fa-reply"></i>', ['action' => 'edit', $enquiry->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Reply']) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $enquiry->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete this enquiry?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} enquiries total') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>