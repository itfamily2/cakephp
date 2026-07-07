<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\EmailTemplate> $emailTemplates
 */
$this->assign('title', 'Email Templates');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-regular fa-envelope text-primary me-2"></i>Email Templates
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/') ?>" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Email Templates</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i>New Template
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark">Template Directory</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th class="px-4 py-3"><?= $this->Paginator->sort('name') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('subject') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('created') ?></th>
                        <th class="text-end px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emailTemplates as $template): ?>
                    <tr>
                        <td class="px-4 fw-bold text-dark">
                            <i class="fa-solid fa-file-code text-muted me-2"></i><?= h($template->name) ?>
                        </td>
                        <td class="text-muted"><?= h($template->subject) ?></td>
                        <td class="text-muted small">
                            <?= h($template->created->format('M d, Y')) ?>
                        </td>
                        <td class="text-end px-4">
                            <div class="btn-group" role="group">
                                <a href="<?= $this->Url->build(['action' => 'view', $template->id]) ?>" class="btn btn-sm btn-light border shadow-sm" title="View">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </a>
                                <a href="<?= $this->Url->build(['action' => 'edit', $template->id]) ?>" class="btn btn-sm btn-light border shadow-sm" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-warning"></i>
                                </a>
                                <?= $this->Form->postLink(
                                    '<i class="fa-solid fa-trash text-danger"></i>',
                                    ['action' => 'delete', $template->id],
                                    [
                                        'confirm' => __('Are you sure you want to delete # {0}?', $template->id),
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