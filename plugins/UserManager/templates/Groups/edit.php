<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group $group
 * @var string[]|\Cake\Collection\CollectionInterface $parentGroups
 */
$this->assign('title', 'Edit Group');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Groups</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Group</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-users-rectangle text-primary me-2"></i>Edit Group: <?= h($group->name) ?></h4>
    </div>
    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="glass-card p-4">
            <?= $this->Form->create($group, ['id' => 'edit-group-form']) ?>
            
            <div id="form-alert-container"></div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <?= $this->Form->control('name', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Group Name'],
                            'placeholder' => 'Enter group name (e.g. Finance, Support)'
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <?= $this->Form->control('parent_id', [
                            'options' => $parentGroups,
                            'empty' => '(No Parent - Top Level Group)',
                            'class' => 'form-select',
                            'label' => ['class' => 'form-label', 'text' => 'Parent Group']
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check form-switch mt-2 p-3 rounded" style="background-color: var(--bg-surface-2); border: 1px solid var(--border-color);">
                    <?= $this->Form->control('registration_allowed', [
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'label' => ['class' => 'form-check-label fw-semibold', 'text' => 'Allow Public Registration to this Group'],
                        'templates' => [
                            'inputContainer' => '{{content}}'
                        ]
                    ]) ?>
                    <small class="d-block text-muted mt-1 ms-4 ps-2">If enabled, new users can select this group during registration.</small>
                </div>
            </div>

            <hr class="my-4">
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4" id="btn-save-group">
                    <i class="fa-solid fa-save me-2"></i>Save Changes
                </button>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="glass-card p-4 bg-primary-light">
            <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-lightbulb me-2"></i>About Groups</h6>
            <p class="text-muted small mb-3">
                Groups allow you to organize users hierarchically. A group can have a parent group, creating a nested tree structure.
            </p>
            <ul class="text-muted small ps-3 mb-0">
                <li class="mb-2"><strong>Top-level groups</strong> have no parent and represent main departments.</li>
                <li class="mb-2"><strong>Sub-groups</strong> inherit permissions from parent groups in some configurations.</li>
                <li><strong>Public registration</strong> allows users to select this group when signing up. Be careful with administrative groups!</li>
            </ul>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#edit-group-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $('#btn-save-group');
        const $alertContainer = $('#form-alert-container');
        
        // Reset state
        $btn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);
        $alertContainer.empty();
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();
        
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    $alertContainer.html(`
                        <div class="alert alert-success d-flex align-items-center mb-4">
                            <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                            <div>${response.message} Redirecting...</div>
                        </div>
                    `);
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1000);
                }
            },
            error: function(xhr) {
                $btn.html('<i class="fa-solid fa-save me-2"></i>Save Changes').prop('disabled', false);
                
                let errorMsg = 'An unexpected error occurred. Please try again.';
                
                if (xhr.responseJSON) {
                    const data = xhr.responseJSON;
                    errorMsg = data.message || errorMsg;
                    
                    // Display field validation errors
                    if (data.errors) {
                        for (const field in data.errors) {
                            const $field = $form.find(`[name="${field}"]`);
                            if ($field.length) {
                                $field.addClass('is-invalid');
                                const errorText = Object.values(data.errors[field]).join(', ');
                                $field.after(`<div class="invalid-feedback">${errorText}</div>`);
                            }
                        }
                    }
                }
                
                $alertContainer.html(`
                    <div class="alert alert-danger d-flex align-items-center mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
                        <div>${errorMsg}</div>
                    </div>
                `);
            }
        });
    });
});
</script>
