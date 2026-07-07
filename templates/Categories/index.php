<?php $this->assign('title', 'Categories'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-tags text-info me-2"></i>Categories</h4>
        <p class="text-muted mb-0">Organise your product catalog with hierarchical categories.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>New Category
    </a>
</div>
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Search categories..." value="<?= h($this->request->getQuery('search')) ?>">
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
                    <th class="ps-4 py-3"><?= $this->Paginator->sort('name', 'Category') ?></th>
                    <th>Slug</th>
                    <th>Parent</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories) || !count($categories)): ?>
                <tr><td colspan="6" class="text-center text-muted py-5"><i class="fa-solid fa-tags fa-2x d-block mb-2 opacity-50"></i>No categories found</td></tr>
                <?php else: ?>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-semibold"><?= h($category->name) ?></div>
                        <?php if (!empty($category->description)): ?>
                            <div class="text-muted small"><?= h(mb_substr($category->description, 0, 50)) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><code class="text-info small"><?= h($category->slug) ?></code></td>
                    <td class="text-muted small"><?= $category->hasValue('parent_category') ? h($category->parent_category->name) : '<span class="text-muted">—</span>' ?></td>
                    <td><span class="badge bg-secondary bg-opacity-30 text-muted"><?= isset($category->product_count) ? $category->product_count : '—' ?></span></td>
                    <td>
                        <?php $active = $category->is_active ?? true; ?>
                        <span class="badge <?= $active ? 'bg-success' : 'bg-secondary' ?> bg-opacity-20 <?= $active ? 'text-success' : 'text-secondary' ?>">
                            <?= $active ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $category->id], ['class' => 'btn btn-sm btn-outline-info', 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $category->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $category->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete category "' . h($category->name) . '"?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} categories') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>