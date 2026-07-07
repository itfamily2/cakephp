<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmailTemplate $emailTemplate
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
$this->assign('title', 'Edit Email Template');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-regular fa-envelope text-primary me-2"></i>Edit Template: <?= h($emailTemplate->name) ?>
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/') ?>" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Templates</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
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

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-5">
        <?= $this->Form->create($emailTemplate, ['class' => 'needs-validation']) ?>
        
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <?= $this->Form->control('name', [
                    'class' => 'form-control shadow-sm',
                    'label' => ['class' => 'form-label fw-bold text-muted small'],
                    'required' => true
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('subject', [
                    'class' => 'form-control shadow-sm',
                    'label' => ['class' => 'form-label fw-bold text-muted small'],
                    'required' => true
                ]) ?>
            </div>
        </div>

        <div class="mb-5">
            <label class="form-label fw-bold text-muted small">Template Body (HTML)</label>
            <?= $this->Form->control('body', [
                'type' => 'textarea',
                'id' => 'editor',
                'class' => 'form-control shadow-sm',
                'label' => false,
                'rows' => 15
            ]) ?>
        </div>

        <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light border shadow-sm px-4">Cancel</a>
            <button type="submit" class="btn btn-primary shadow-sm px-4 fw-bold">
                <i class="fa-solid fa-save me-2"></i>Update Template
            </button>
        </div>
        
        <?= $this->Form->end() ?>
    </div>
</div>

<!-- CKEditor 5 Editor -->
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@39.0.1/build/ckeditor.js"></script>
<style>
.ck-editor__editable_inline {
    min-height: 400px;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
});
</script>
