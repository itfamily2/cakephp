<?php $this->assign('title', 'Email Signatures'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-signature text-warning me-2"></i>Email Signatures</h4>
        <p class="text-muted mb-0">Create and manage professional email signatures for outbound communications.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>New Signature
    </a>
</div>
<div class="row g-4">
    <?php if (empty($emailSignatures) || !count($emailSignatures)): ?>
    <div class="col-12">
        <div class="glass-card p-5 text-center text-muted">
            <i class="fa-solid fa-signature fa-3x d-block mb-3 opacity-30"></i>
            <p>No signatures created yet. <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="text-primary">Create your first signature</a></p>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($emailSignatures as $sig): ?>
    <div class="col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 flex-shrink-0" style="background:rgba(245,158,11,0.15);">
                        <i class="fa-solid fa-signature" style="color:#f59e0b;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0"><?= h($sig->name) ?></h6>
                        <?php if ($sig->is_default ?? false): ?>
                            <span class="badge bg-success bg-opacity-20 text-success small"><i class="fa-solid fa-star me-1"></i>Default</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="p-3 rounded mb-3" style="background:rgba(0,0,0,0.2);border:1px solid var(--border-color);">
                <div class="text-muted small" style="max-height:80px;overflow:hidden;">
                    <?= h(mb_substr(strip_tags($sig->content ?? '(No content)'), 0, 150)) ?>...
                </div>
            </div>
            <div class="d-flex gap-2">
                <?= $this->Html->link('<i class="fa-solid fa-eye me-1"></i>Preview', ['action' => 'view', $sig->id], ['class' => 'btn btn-sm btn-outline-info flex-fill ajax-modal-link', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fa-solid fa-pen me-1"></i>Edit', ['action' => 'edit', $sig->id], ['class' => 'btn btn-sm btn-outline-primary flex-fill ajax-modal-link', 'escape' => false]) ?>
                <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $sig->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete signature "' . h($sig->name) . '"?']) ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>