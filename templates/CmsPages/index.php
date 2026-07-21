<?php $this->assign('title', 'CMS Pages'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-file-lines text-info me-2"></i>CMS Pages</h4>
        <p class="text-muted mb-0">Manage website content pages, SEO metadata and publishing status.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>New Page
    </a>
</div>
<div class="glass-card p-3 mb-4">
    <form method="get" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control border-secondary bg-transparent text-white"
                   placeholder="Search by title or slug..." value="<?= h($this->request->getQuery('search')) ?>">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select border-secondary bg-transparent text-white">
                <option value="">All Status</option>
                <option value="published" <?= $this->request->getQuery('status') === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="draft" <?= $this->request->getQuery('status') === 'draft' ? 'selected' : '' ?>>Draft</option>
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
                    <th class="ps-4 py-3"><?= $this->Paginator->sort('title', 'Title') ?></th>
                    <th>Slug / URL</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th><?= $this->Paginator->sort('modified', 'Last Updated') ?></th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cmsPages) || !count($cmsPages)): ?>
                <tr><td colspan="7" class="text-center text-muted py-5"><i class="fa-solid fa-file-circle-xmark fa-2x d-block mb-2 opacity-50"></i>No pages created yet</td></tr>
                <?php else: ?>
                <?php foreach ($cmsPages as $page): ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-semibold"><?= h($page->title) ?></div>
                        <?php if (!empty($page->meta_title)): ?>
                            <div class="text-muted small"><i class="fa-brands fa-google me-1"></i><?= h(substr($page->meta_title, 0, 40)) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><code class="text-info small">/<?= h($page->slug) ?></code></td>
                    <td><span class="badge bg-secondary bg-opacity-30 text-muted small"><?= h($page->template ?? 'default') ?></span></td>
                    <td>
                        <?php $pub = $page->status ?? ($page->is_published ? 'published' : 'draft'); ?>
                        <span class="badge <?= $pub === 'published' ? 'bg-success' : 'bg-warning' ?> bg-opacity-20 <?= $pub === 'published' ? 'text-success' : 'text-warning' ?>">
                            <i class="fa-solid fa-<?= $pub === 'published' ? 'globe' : 'pen' ?> me-1"></i><?= ucfirst($pub) ?>
                        </span>
                    </td>
                    <td class="text-muted small"><?= h($page->user->username ?? 'System') ?></td>
                    <td class="text-muted small"><?= $page->modified ? date('M d, Y', strtotime($page->modified)) : '-' ?></td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $page->id], ['class' => 'btn btn-sm btn-outline-info ajax-modal-link', 'escape' => false]) ?>
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $page->id], ['class' => 'btn btn-sm btn-outline-primary ajax-modal-link', 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $page->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete page "' . h($page->title) . '"?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
        <p class="text-muted small mb-0"><?= $this->Paginator->counter('{{count}} pages total') ?></p>
        <ul class="pagination pagination-sm mb-0">
            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
        </ul>
    </div>
</div>