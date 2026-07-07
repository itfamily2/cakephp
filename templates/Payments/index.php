<?php $this->assign('title', 'Payments'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-credit-card text-success me-2"></i>Payments</h4>
        <p class="text-muted mb-0">Track all payment transactions, statuses and revenue.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Record Payment
    </a>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="glass-card p-4 text-center">
            <div class="text-success fw-bold fs-4">₹<?= number_format($totalRevenue ?? 0, 2) ?></div>
            <div class="text-muted small mt-1"><i class="fa-solid fa-check-circle text-success me-1"></i>Total Collected</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-4 text-center">
            <div class="text-warning fw-bold fs-4"><?= number_format($pendingCount ?? 0) ?></div>
            <div class="text-muted small mt-1"><i class="fa-solid fa-clock text-warning me-1"></i>Pending Payments</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-4 text-center">
            <div class="text-danger fw-bold fs-4">₹<?= number_format($refundTotal ?? 0, 2) ?></div>
            <div class="text-muted small mt-1"><i class="fa-solid fa-rotate-left text-danger me-1"></i>Total Refunded</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Order # or transaction ID..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select border-secondary bg-transparent text-white">
                <option value="">All Status</option>
                <?php foreach (['completed','pending','failed','refunded'] as $s): ?>
                    <option value="<?= $s ?>" <?= $this->request->getQuery('status') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="method" class="form-select border-secondary bg-transparent text-white">
                <option value="">All Methods</option>
                <?php foreach (['Cash','Card','UPI','Bank Transfer','Cheque'] as $m): ?>
                    <option value="<?= $m ?>" <?= $this->request->getQuery('method') === $m ? 'selected' : '' ?>><?= $m ?></option>
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
                    <th class="ps-4 py-3">Transaction</th>
                    <th>Order</th>
                    <th><?= $this->Paginator->sort('amount', 'Amount') ?></th>
                    <th>Method</th>
                    <th>Status</th>
                    <th><?= $this->Paginator->sort('created', 'Date') ?></th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments) || !count($payments)): ?>
                <tr><td colspan="7" class="text-center text-muted py-5"><i class="fa-solid fa-credit-card fa-2x d-block mb-2 opacity-50"></i>No payment records found</td></tr>
                <?php else: ?>
                <?php foreach ($payments as $payment): ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold font-monospace text-info small"><?= h($payment->transaction_id ?? '#' . $payment->id) ?></div>
                        <div class="text-muted small"><?= h($payment->gateway ?? 'Manual') ?></div>
                    </td>
                    <td class="fw-semibold text-primary"><?= $payment->hasValue('order') ? '#' . h($payment->order->order_number ?? $payment->order_id) : '—' ?></td>
                    <td class="fw-bold text-success">₹<?= number_format($payment->amount ?? 0, 2) ?></td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-30 text-white small"><?= h($payment->payment_method ?? $payment->method ?? 'N/A') ?></span>
                    </td>
                    <td>
                        <?php
                        $pColors = ['completed'=>'success','paid'=>'success','pending'=>'warning','failed'=>'danger','refunded'=>'info'];
                        $st = strtolower($payment->status ?? 'pending');
                        $pc = $pColors[$st] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $pc ?> bg-opacity-20 text-<?= $pc ?>"><?= ucfirst($st) ?></span>
                    </td>
                    <td class="text-muted small"><?= $payment->created ? date('M d, Y', strtotime($payment->created)) : '-' ?></td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $payment->id], ['class' => 'btn btn-sm btn-outline-info', 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $payment->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} payment records') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>