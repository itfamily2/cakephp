<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmailTemplate $emailTemplate
 */
$this->assign('title', 'Email Template - ' . h($emailTemplate->name));
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-regular fa-envelope text-primary me-2"></i><?= h($emailTemplate->name) ?>
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/') ?>" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Templates</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $emailTemplate->id]) ?>" class="btn btn-warning shadow-sm fw-bold">
            <i class="fa-solid fa-pen-to-square me-2"></i>Edit
        </a>
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-2"></i>Delete',
            ['action' => 'delete', $emailTemplate->id],
            [
                'confirm' => __('Are you sure you want to delete # {0}?', $emailTemplate->id),
                'class' => 'btn btn-outline-danger shadow-sm fw-bold',
                'escape' => false
            ]
        ) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Template Info Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-3">Template Info</h6>
                
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Name</span>
                        <span class="text-dark fw-medium"><?= h($emailTemplate->name) ?></span>
                    </li>
                    <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Subject</span>
                        <span class="text-dark fw-medium"><?= h($emailTemplate->subject) ?></span>
                    </li>
                    <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Created</span>
                        <span class="text-dark fw-medium"><?= h($emailTemplate->created->format('M d, Y g:i A')) ?></span>
                    </li>
                    <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Modified</span>
                        <span class="text-dark fw-medium"><?= h($emailTemplate->modified->timeAgoInWords()) ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Preview Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-eye text-primary me-2"></i>Rendered Preview</h6>
            </div>
            <div class="card-body p-5 bg-light" style="min-height: 400px; border-radius: 0 0 0.375rem 0.375rem;">
                <div class="bg-white p-4 shadow-sm" style="border: 1px solid #e3e6f0;">
                    <!-- We output the unescaped HTML here because this is meant to be a rendered template -->
                    <?= $emailTemplate->body ?>
                </div>
            </div>
        </div>
    </div>
</div>