<?php $this->assign('title', 'Email Templates'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-envelope text-info me-2"></i>Email Templates</h4>
        <p class="text-muted mb-0">Manage reusable transactional and marketing email templates.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>New Template
    </a>
</div>
<div class="row g-4">
    <?php if (empty($emailTemplates) || !count($emailTemplates)): ?>
    <div class="col-12">
        <div class="glass-card p-5 text-center text-muted">
            <i class="fa-solid fa-envelope-open fa-3x d-block mb-3 opacity-30"></i>
            <p>No email templates yet. <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="text-primary">Create your first template</a></p>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($emailTemplates as $template): ?>
    <div class="col-md-6 col-xl-4">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-3 p-3 flex-shrink-0" style="background:rgba(6,182,212,0.15);">
                    <i class="fa-solid fa-envelope-open-text" style="color:#06b6d4;"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <h6 class="fw-bold mb-0 text-truncate"><?= h($template->name) ?></h6>
                    <div class="text-muted small"><?= h($template->slug ?? strtolower(str_replace(' ', '_', $template->name))) ?></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="text-muted small mb-1"><strong class="text-white">Subject:</strong></div>
                <div class="text-muted small text-truncate"><?= h($template->subject ?? '(No subject)') ?></div>
            </div>
            <div class="d-flex align-items-center gap-2 mt-auto pt-3" style="border-top:1px solid var(--border-color);">
                <?= $this->Html->link('<i class="fa-solid fa-eye me-1"></i>Preview', ['action' => 'view', $template->id], ['class' => 'btn btn-sm btn-outline-info flex-fill', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fa-solid fa-pen me-1"></i>Edit', ['action' => 'edit', $template->id], ['class' => 'btn btn-sm btn-outline-primary flex-fill', 'escape' => false]) ?>
                <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $template->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete template "' . h($template->name) . '"?']) ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>