<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 */
$this->assign('title', 'Order Details - ' . h($order->order_number));
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-solid fa-file-lines text-primary me-2"></i>Order #<?= h($order->order_number) ?>
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $order->id]) ?>" class="btn btn-warning shadow-sm">
            <i class="fa-solid fa-pen-to-square me-2"></i>Edit
        </a>
        <a href="<?= $this->Url->build(['controller' => 'Invoices', 'action' => 'add', '?' => ['order_id' => $order->id]]) ?>" class="btn btn-success shadow-sm">
            <i class="fa-solid fa-file-invoice me-2"></i>Generate Invoice
        </a>
    </div>
</div>

<div class="row">
    <!-- Order Details -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Line Items</h6>
                <span class="badge bg-secondary rounded-pill px-3 py-2"><?= $order->hasValue('order_items') ? count($order->order_items) : 0 ?> Items</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Tax</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end px-4">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($order->order_items)) : ?>
                                <?php foreach ($order->order_items as $item) : ?>
                                    <tr>
                                        <td class="px-4 fw-medium text-dark">
                                            <?= $item->hasValue('product') ? h($item->product->name) : 'Product #' . $item->product_id ?>
                                        </td>
                                        <td class="text-center"><?= $this->Number->format($item->quantity) ?></td>
                                        <td class="text-end"><?= $this->Number->currency($item->price, 'USD') ?></td>
                                        <td class="text-end"><?= $this->Number->currency($item->tax, 'USD') ?></td>
                                        <td class="text-end text-danger"><?= $this->Number->currency($item->discount, 'USD') ?></td>
                                        <td class="text-end px-4 fw-bold"><?= $this->Number->currency($item->line_total, 'USD') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-box-open fs-3 mb-2 d-block text-black-50"></i>
                                        No items found in this order.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">Subtotal</td>
                                <td colspan="2" class="text-end px-4"><?= $this->Number->currency($order->total, 'USD') ?></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Tax</td>
                                <td colspan="2" class="text-end px-4"><?= $this->Number->currency($order->tax, 'USD') ?></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end text-danger">Discount</td>
                                <td colspan="2" class="text-end text-danger px-4">-<?= $this->Number->currency($order->discount, 'USD') ?></td>
                            </tr>
                            <tr class="fs-5 text-dark">
                                <td colspan="4" class="text-end">Grand Total</td>
                                <td colspan="2" class="text-end px-4"><?= $this->Number->currency($order->grand_total, 'USD') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary Sidebar -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <?php
                        $badgeClass = 'bg-secondary';
                        if ($order->status === 'Completed') $badgeClass = 'bg-success';
                        elseif ($order->status === 'Processing') $badgeClass = 'bg-primary';
                        elseif ($order->status === 'Cancelled') $badgeClass = 'bg-danger';
                        elseif ($order->status === 'Draft') $badgeClass = 'bg-warning text-dark';
                    ?>
                    <span class="badge <?= $badgeClass ?> rounded-pill px-4 py-2 fs-5">
                        <?= h($order->status) ?>
                    </span>
                </div>
                <p class="text-muted small mb-0">Created on <?= h($order->created->format('M d, Y h:i A')) ?></p>
            </div>
        </div>

        <!-- Customer & Info Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 fw-bold text-dark">Order Information</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="fa-solid fa-user me-2"></i>Customer</span>
                        <span class="fw-medium text-dark">
                            <?= $order->hasValue('user') ? h($order->user->username) : 'Guest/System' ?>
                        </span>
                    </li>
                    <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="fa-solid fa-hashtag me-2"></i>Order Ref</span>
                        <span class="fw-medium text-dark"><?= h($order->order_number) ?></span>
                    </li>
                    <?php if ($order->accepted_by): ?>
                    <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="fa-solid fa-check-double me-2"></i>Accepted At</span>
                        <span class="fw-medium text-dark"><?= $order->accepted_at ? h($order->accepted_at->format('M d, Y')) : '' ?></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <?php if (!empty($order->notes)): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 fw-bold text-dark"><i class="fa-regular fa-comment-dots me-2"></i>Notes</h6>
            </div>
            <div class="card-body p-4 bg-light">
                <p class="mb-0 text-muted fst-italic"><?= nl2br(h($order->notes)) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>