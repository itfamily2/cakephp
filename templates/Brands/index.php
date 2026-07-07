<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Brand> $brands
 */
$this->assign('title', 'Brands Management');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-solid fa-copyright text-primary me-2"></i>Brands
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/') ?>" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Brands</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i>New Brand
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark">Brand Directory</h6>
        <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
            <input type="text" class="form-control border-start-0 bg-light" placeholder="Search brands...">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th class="px-4 py-3"><?= $this->Paginator->sort('id') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('name') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('slug') ?></th>
                        <th class="py-3">Status</th>
                        <th class="py-3"><?= $this->Paginator->sort('created') ?></th>
                        <th class="text-end px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($brands as $brand): ?>
                    <tr>
                        <td class="px-4 fw-medium text-muted">#<?= $this->Number->format($brand->id) ?></td>
                        <td class="fw-bold text-dark">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-tags text-primary"></i>
                                </div>
                                <?= h($brand->name) ?>
                            </div>
                        </td>
                        <td class="text-muted"><i class="fa-solid fa-link me-2 small"></i><?= h($brand->slug) ?></td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> Active
                            </span>
                        </td>
                        <td class="text-muted small">
                            <?= h($brand->created->format('M d, Y')) ?>
                        </td>
                        <td class="text-end px-4">
                            <div class="btn-group" role="group">
                                <a href="<?= $this->Url->build(['action' => 'view', $brand->id]) ?>" class="btn btn-sm btn-light border shadow-sm" title="View">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </a>
                                <a href="<?= $this->Url->build(['action' => 'edit', $brand->id]) ?>" class="btn btn-sm btn-light border shadow-sm" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-warning"></i>
                                </a>
                                <?= $this->Form->postLink(
                                    '<i class="fa-solid fa-trash text-danger"></i>',
                                    ['action' => 'delete', $brand->id],
                                    [
                                        'confirm' => __('Are you sure you want to delete # {0}?', $brand->id),
                                        'class' => 'btn btn-sm btn-light border shadow-sm',
                                        'escape' => false,
                                        'title' => 'Delete'
                                    ]
                                ) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top py-3">
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted small mb-0"><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <?= $this->Paginator->first('<< ' . __('first'), ['class' => 'page-item', 'a' => ['class' => 'page-link']]) ?>
                    <?= $this->Paginator->prev('< ' . __('previous'), ['class' => 'page-item', 'a' => ['class' => 'page-link']]) ?>
                    <?= $this->Paginator->numbers(['class' => 'page-item', 'a' => ['class' => 'page-link']]) ?>
                    <?= $this->Paginator->next(__('next') . ' >', ['class' => 'page-item', 'a' => ['class' => 'page-link']]) ?>
                    <?= $this->Paginator->last(__('last') . ' >>', ['class' => 'page-item', 'a' => ['class' => 'page-link']]) ?>
                </ul>
            </nav>
        </div>
    </div>
</div>