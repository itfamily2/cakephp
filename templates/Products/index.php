<?php $this->assign('title', 'Products'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-box text-warning me-2"></i>Products</h4>
        <p class="text-muted mb-0">Manage your product catalog and inventory.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Add Product
    </a>
</div>
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white" 
                   placeholder="Search by name or SKU..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select border-secondary bg-transparent text-white">
                <option value="">All Status</option>
                <option value="1" <?= $this->request->getQuery('status') === '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= $this->request->getQuery('status') === '0' ? 'selected' : '' ?>>Inactive</option>
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
                    <th class="ps-4 py-3"><?= $this->Paginator->sort('name', 'Product') ?></th>
                    <th><?= $this->Paginator->sort('sku', 'SKU') ?></th>
                    <th>Category / Brand</th>
                    <th><?= $this->Paginator->sort('price', 'Price') ?></th>
                    <th><?= $this->Paginator->sort('stock', 'Stock') ?></th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-semibold"><?= h($product->name) ?></div>
                        <div class="text-muted small"><?= h($product->slug) ?></div>
                    </td>
                    <td><code class="text-warning small"><?= h($product->sku) ?></code></td>
                    <td>
                        <div class="small"><?= $product->hasValue('category') ? h($product->category->name) : '<span class="text-muted">—</span>' ?></div>
                        <div class="text-muted small"><?= $product->hasValue('brand') ? h($product->brand->name) : '' ?></div>
                    </td>
                    <td class="fw-bold text-success">₹<?= number_format($product->price ?? 0, 2) ?></td>
                    <td>
                        <?php $stock = $product->stock ?? $product->stock_quantity ?? 0; ?>
                        <span class="badge <?= $stock > 10 ? 'bg-success' : ($stock > 0 ? 'bg-warning' : 'bg-danger') ?> bg-opacity-20 <?= $stock > 10 ? 'text-success' : ($stock > 0 ? 'text-warning' : 'text-danger') ?>">
                            <?= number_format($stock) ?> units
                        </span>
                    </td>
                    <td>
                        <?php $active = $product->is_active ?? true; ?>
                        <span class="badge <?= $active ? 'bg-success' : 'bg-secondary' ?> bg-opacity-20 <?= $active ? 'text-success' : 'text-secondary' ?>">
                            <?= $active ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $product->id], ['class' => 'btn btn-sm btn-outline-info', 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $product->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $product->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete "' . h($product->name) . '"?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('Showing {{current}} of {{count}} products') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>