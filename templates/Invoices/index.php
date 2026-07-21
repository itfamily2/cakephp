<?php $this->assign('title', 'Invoices'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-file-invoice-dollar text-info me-2"></i>Invoices</h4>
        <p class="text-muted mb-0">Generate and manage customer invoices and billing records.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>New Invoice
    </a>
</div>
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Search invoice # or customer..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select border-secondary bg-transparent text-white">
                <option value="">All Status</option>
                <?php foreach (['draft','sent','paid','overdue','cancelled'] as $s): ?>
                    <option value="<?= $s ?>" <?= $this->request->getQuery('status') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
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
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;">
                    <th class="ps-4 py-3">Invoice #</th>
                    <th>Customer</th>
                    <th>Order</th>
                    <th><?= $this->Paginator->sort('total_amount', 'Amount') ?></th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($invoices) || !count($invoices)): ?>
                <tr><td colspan="7" class="text-center text-muted py-5"><i class="fa-solid fa-file-invoice fa-2x d-block mb-2 opacity-50"></i>No invoices found</td></tr>
                <?php else: ?>
                <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td class="ps-4 fw-bold text-primary"><?= h($invoice->invoice_number ?? 'INV-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT)) ?></td>
                    <td>
                        <div class="fw-semibold"><?= h($invoice->user->username ?? $invoice->billing_name ?? 'N/A') ?></div>
                        <div class="text-muted small"><?= h($invoice->user->email ?? '') ?></div>
                    </td>
                    <td class="text-muted"><?= !empty($invoice->order_id) ? '#' . h($invoice->order_id) : '—' ?></td>
                    <td class="fw-bold text-success">₹<?= number_format($invoice->total_amount ?? 0, 2) ?></td>
                    <td>
                        <?php
                        $iColors = ['draft'=>'secondary','sent'=>'info','paid'=>'success','overdue'=>'danger','cancelled'=>'warning'];
                        $ist = strtolower($invoice->status ?? 'draft');
                        $ic = $iColors[$ist] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $ic ?> bg-opacity-20 text-<?= $ic ?>"><?= ucfirst($ist) ?></span>
                    </td>
                    <td class="text-muted small">
                        <?php if (!empty($invoice->due_date)): ?>
                            <?php $overdue = strtotime($invoice->due_date) < time() && $ist !== 'paid'; ?>
                            <span class="<?= $overdue ? 'text-danger fw-bold' : '' ?>"><?= date('M d, Y', strtotime($invoice->due_date)) ?></span>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $invoice->id], ['class' => 'btn btn-sm btn-outline-info ajax-modal-link', 'escape' => false, 'title' => 'View']) ?>
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $invoice->id], ['class' => 'btn btn-sm btn-outline-primary ajax-modal-link', 'escape' => false, 'title' => 'Edit']) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $invoice->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete this invoice?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} invoices') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>