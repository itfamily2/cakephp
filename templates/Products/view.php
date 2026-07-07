<?php $this->assign('title', 'Product: ' . h($product->name)); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-0" style="font-size:0.8rem;">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-muted">Products</a></li>
                <li class="breadcrumb-item active text-white"><?= h($product->name) ?></li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0"><?= h($product->name) ?></h4>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $product->id]) ?>" class="btn btn-primary">
            <i class="fa-solid fa-pen me-2"></i>Edit Product
        </a>
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-1"></i> Delete',
            ['action' => 'delete', $product->id],
            ['confirm' => 'Delete this product permanently?', 'class' => 'btn btn-outline-danger', 'escape' => false]
        ) ?>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left: Core Product Info -->
    <div class="col-lg-8">
        <div class="glass-card p-4 mb-4">
            <div class="row g-4 align-items-start">
                <!-- Product image placeholder -->
                <div class="col-sm-3 text-center">
                    <div class="rounded-3 d-flex align-items-center justify-content-center mx-auto"
                         style="width:120px;height:120px;background:rgba(99,102,241,0.15);border:2px solid rgba(99,102,241,0.3);">
                        <i class="fa-solid fa-box fa-3x text-primary opacity-75"></i>
                    </div>
                    <?php if (!empty($product->sku)): ?>
                        <div class="mt-2">
                            <code class="text-warning small"><?= h($product->sku) ?></code>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-sm-9">
                    <h5 class="fw-bold mb-1"><?= h($product->name) ?></h5>
                    <p class="text-muted small mb-3"><?= nl2br(h($product->description ?? 'No description available.')) ?></p>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="text-muted small d-block">Category</label>
                            <span class="badge bg-info bg-opacity-20 text-info p-2">
                                <i class="fa-solid fa-tag me-1"></i><?= h($product->category->name ?? '—') ?>
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <label class="text-muted small d-block">Brand</label>
                            <span class="badge bg-secondary bg-opacity-20 text-white p-2">
                                <i class="fa-solid fa-building me-1"></i><?= h($product->brand->name ?? '—') ?>
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <label class="text-muted small d-block">Status</label>
                            <?php $active = !empty($product->is_active) ? true : false; ?>
                            <span class="badge <?= $active ? 'bg-success' : 'bg-danger' ?> bg-opacity-20 <?= $active ? 'text-success' : 'text-danger' ?> p-2">
                                <i class="fa-solid <?= $active ? 'fa-check-circle' : 'fa-ban' ?> me-1"></i><?= $active ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="glass-card p-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="border-color:var(--border-color)!important;">
                <i class="fa-solid fa-cart-shopping me-2 text-warning"></i>Completed Order History
            </h6>
            <?php if (empty($product->order_items)): ?>
                <div class="text-center text-muted py-4">
                    <i class="fa-solid fa-box-open fa-2x mb-2 opacity-50 d-block"></i>
                    <p class="mb-0 small">No completed orders found for this product.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">
                                <th class="ps-3">Order #</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($product->order_items as $item): ?>
                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-semibold text-info"><?= h($item->order->order_number ?? '#' . $item->order_id) ?></span>
                                    </td>
                                    <td><span class="badge bg-primary bg-opacity-20 text-primary"><?= $item->quantity ?></span></td>
                                    <td class="text-warning fw-semibold">₹<?= number_format($item->price ?? 0, 2) ?></td>
                                    <td class="text-success fw-bold">₹<?= number_format(($item->quantity ?? 1) * ($item->price ?? 0), 2) ?></td>
                                    <td class="text-muted small"><?= isset($item->order->created) ? $item->order->created->format('M d, Y') : '—' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Pricing & Inventory -->
    <div class="col-lg-4">
        <!-- Pricing Card -->
        <div class="glass-card p-4 mb-4" style="border-color:rgba(34,197,94,0.3);">
            <h6 class="fw-bold mb-3 text-success"><i class="fa-solid fa-Indian-rupee-sign me-2"></i>Pricing</h6>
            <div class="d-flex align-items-end gap-2 mb-2">
                <span class="fs-2 fw-bold text-success">₹<?= number_format($product->price ?? 0, 2) ?></span>
                <?php if (!empty($product->compare_price) && $product->compare_price > $product->price): ?>
                    <span class="text-muted text-decoration-line-through mb-1">₹<?= number_format($product->compare_price, 2) ?></span>
                    <span class="badge bg-danger text-white mb-1">
                        <?= round((1 - $product->price / $product->compare_price) * 100) ?>% OFF
                    </span>
                <?php endif; ?>
            </div>
            <?php if (!empty($product->cost_price)): ?>
                <div class="text-muted small">Cost Price: ₹<?= number_format($product->cost_price, 2) ?></div>
                <div class="text-info small mt-1">Margin: ₹<?= number_format($product->price - $product->cost_price, 2) ?> (<?= $product->cost_price > 0 ? round((($product->price - $product->cost_price) / $product->cost_price) * 100) : 0 ?>%)</div>
            <?php endif; ?>
        </div>

        <!-- Stock Card -->
        <div class="glass-card p-4 mb-4" style="border-color:<?= ($product->stock ?? 0) > 10 ? 'rgba(99,102,241,0.3)' : 'rgba(239,68,68,0.4)' ?>;">
            <h6 class="fw-bold mb-3 text-info"><i class="fa-solid fa-warehouse me-2"></i>Inventory</h6>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small">Current Stock</span>
                <span class="fs-4 fw-bold <?= ($product->stock ?? 0) > 10 ? 'text-success' : (($product->stock ?? 0) > 0 ? 'text-warning' : 'text-danger') ?>">
                    <?= number_format($product->stock ?? 0) ?>
                </span>
            </div>
            <?php $stockPct = min(100, max(0, ($product->stock ?? 0) / 100 * 100)); ?>
            <div class="progress mb-2" style="height:6px;background:rgba(255,255,255,0.1);">
                <div class="progress-bar <?= ($product->stock ?? 0) > 50 ? 'bg-success' : (($product->stock ?? 0) > 10 ? 'bg-warning' : 'bg-danger') ?>"
                     style="width:<?= $stockPct ?>%"></div>
            </div>
            <?php if (($product->stock ?? 0) <= 0): ?>
                <div class="alert p-2 mb-0" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;font-size:0.8rem;">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Out of Stock
                </div>
            <?php elseif (($product->stock ?? 0) <= 10): ?>
                <div class="alert p-2 mb-0" style="background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.3);color:#fbbf24;font-size:0.8rem;">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Low Stock Warning
                </div>
            <?php endif; ?>
        </div>

        <!-- Metadata -->
        <div class="glass-card p-4">
            <h6 class="fw-bold mb-3 text-muted"><i class="fa-solid fa-circle-info me-2"></i>Metadata</h6>
            <div class="row g-2">
                <div class="col-6">
                    <label class="text-muted" style="font-size:0.7rem;text-transform:uppercase;">Product ID</label>
                    <div class="fw-bold font-monospace">#<?= $product->id ?></div>
                </div>
                <div class="col-6">
                    <label class="text-muted" style="font-size:0.7rem;text-transform:uppercase;">Created</label>
                    <div class="fw-semibold small"><?= $product->created ? $product->created->format('M d, Y') : '—' ?></div>
                </div>
                <div class="col-6">
                    <label class="text-muted" style="font-size:0.7rem;text-transform:uppercase;">Weight</label>
                    <div class="fw-semibold"><?= !empty($product->weight) ? h($product->weight) . ' g' : '—' ?></div>
                </div>
                <div class="col-6">
                    <label class="text-muted" style="font-size:0.7rem;text-transform:uppercase;">Modified</label>
                    <div class="fw-semibold small"><?= $product->modified ? $product->modified->format('M d, Y') : '—' ?></div>
                </div>
            </div>
        </div>
    </div>
</div>