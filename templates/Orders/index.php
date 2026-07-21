<?php $this->assign('title', 'Orders'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-cart-shopping text-success me-2"></i>Orders</h4>
        <p class="text-muted mb-0">Track and manage customer orders across all states.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>New Order
    </a>
</div>
<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <?php
    $statusStats = [
        ['label' => 'Pending', 'color' => 'warning', 'icon' => 'clock'],
        ['label' => 'Processing', 'color' => 'info', 'icon' => 'spinner'],
        ['label' => 'Delivered', 'color' => 'success', 'icon' => 'check-circle'],
        ['label' => 'Cancelled', 'color' => 'danger', 'icon' => 'xmark'],
    ];
    foreach ($statusStats as $s):
    ?>
    <div class="col-md-3">
        <div class="glass-card p-3 d-flex align-items-center gap-3">
            <div class="rounded-3 p-2" style="background:rgba(var(--bs-<?= $s['color'] ?>-rgb),0.15);">
                <i class="fa-solid fa-<?= $s['icon'] ?> text-<?= $s['color'] ?>"></i>
            </div>
            <div>
                <div class="fw-bold"><?= $s['label'] ?></div>
                <div class="text-muted small">Orders</div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<!-- Filters -->
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Order # or customer email..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select border-secondary bg-transparent text-white">
                <option value="">All Status</option>
                <?php foreach (['Pending','Processing','Shipped','Delivered','Cancelled','Refunded'] as $st): ?>
                    <option value="<?= $st ?>" <?= $this->request->getQuery('status') === $st ? 'selected' : '' ?>><?= $st ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="from" class="form-control border-secondary bg-transparent text-white" value="<?= h($this->request->getQuery('from')) ?>">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control border-secondary bg-transparent text-white" value="<?= h($this->request->getQuery('to')) ?>">
        </div>
        <div class="col-md-2 d-flex gap-2">
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
                    <th class="ps-4 py-3"><?= $this->Paginator->sort('order_number', 'Order #') ?></th>
                    <th>Customer</th>
                    <th><?= $this->Paginator->sort('total', 'Total') ?></th>
                    <th><?= $this->Paginator->sort('status', 'Status') ?></th>
                    <th>Payment</th>
                    <th><?= $this->Paginator->sort('created', 'Date') ?></th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders) || !count($orders)): ?>
                <tr><td colspan="7" class="text-center text-muted py-5"><i class="fa-solid fa-inbox fa-2x d-block mb-2 opacity-50"></i>No orders found</td></tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="ps-4 fw-bold text-primary">#<?= h($order->order_number ?? $order->id) ?></td>
                    <td>
                        <div class="fw-semibold"><?= h($order->user->username ?? 'Guest') ?></div>
                        <div class="text-muted small"><?= h($order->user->email ?? '') ?></div>
                    </td>
                    <td class="fw-bold text-success">₹<?= number_format($order->total ?? 0, 2) ?></td>
                    <td>
                        <?php
                        $colors = ['Pending'=>'warning','Processing'=>'info','Shipped'=>'primary','Delivered'=>'success','Cancelled'=>'danger','Refunded'=>'secondary'];
                        $c = $colors[$order->status ?? 'Pending'] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $c ?> bg-opacity-20 text-<?= $c ?>"><?= h($order->status ?? 'Pending') ?></span>
                    </td>
                    <td>
                        <?php $paid = $order->payment_status ?? 'Unpaid'; ?>
                        <span class="badge <?= $paid === 'Paid' ? 'bg-success' : 'bg-warning' ?> bg-opacity-20 <?= $paid === 'Paid' ? 'text-success' : 'text-warning' ?>"><?= h($paid) ?></span>
                    </td>
                    <td class="text-muted small"><?= $order->created ? date('M d, Y', strtotime($order->created)) : '-' ?></td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $order->id], ['class' => 'btn btn-sm btn-outline-info ajax-modal-link', 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $order->id], ['class' => 'btn btn-sm btn-outline-primary ajax-modal-link', 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $order->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete order #' . h($order->order_number ?? $order->id) . '?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('Showing {{current}} of {{count}} orders') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>