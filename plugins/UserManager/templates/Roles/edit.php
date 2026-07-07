<?php $this->assign('title', 'Edit Role'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-user-shield text-primary me-2"></i>Edit <?= h($role->name) ?> Role</h4>
        <p class="text-muted mb-0">Update role details and bulk-assign module permissions.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'view', $role->id]) ?>" class="btn btn-outline-info">
            <i class="fa-solid fa-eye me-2"></i>View
        </a>
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-2"></i>Delete',
            ['action' => 'delete', $role->id],
            ['confirm' => __('Are you sure you want to delete # {0}?', $role->id), 'class' => 'btn btn-outline-danger', 'escape' => false]
        ) ?>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="glass-card p-4 h-100">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="border-color:var(--border-color)!important;">Basic Details</h6>
            <?= $this->Form->create($role) ?>
            
            <div class="mb-3">
                <?= $this->Form->control('name', [
                    'class' => 'form-control border-secondary bg-transparent text-white',
                    'label' => ['class' => 'form-label text-muted small']
                ]) ?>
            </div>
            
            <div class="mb-3">
                <?= $this->Form->control('description', [
                    'class' => 'form-control border-secondary bg-transparent text-white',
                    'label' => ['class' => 'form-label text-muted small'],
                    'type' => 'textarea',
                    'rows' => 4
                ]) ?>
            </div>
            
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" style="border-color:var(--border-color)!important;">
                <h6 class="fw-bold mb-0">Module Permissions</h6>
                <a href="<?= $this->Url->build(['controller' => 'Permissions', 'action' => 'index', '?' => ['role_id' => $role->id]]) ?>" class="btn btn-sm btn-outline-info">
                    Advanced Rules
                </a>
            </div>
            <p class="text-muted small mb-3">Select the core modules this role should have full access to. This assigns a wildcard (<code>*</code>) permission for the selected controller.</p>
            
            <div class="row g-2">
                <?php foreach ($controllersList ?? [] as $ctrl => $label): ?>
                    <?php $isChecked = in_array($ctrl, $currentControllers ?? []); ?>
                    <div class="col-sm-6">
                        <div class="form-check p-2 rounded" style="background:rgba(0,0,0,0.2); border:1px solid <?= $isChecked ? 'var(--bs-primary)' : 'var(--border-color)' ?>;">
                            <input class="form-check-input ms-1 me-2" type="checkbox" name="selected_controllers[]" value="<?= h($ctrl) ?>" id="ctrl_<?= h($ctrl) ?>" <?= $isChecked ? 'checked' : '' ?>>
                            <label class="form-check-label text-white d-block w-100 cursor-pointer" for="ctrl_<?= h($ctrl) ?>">
                                <?= h($label) ?> <small class="text-muted d-block">(<?= h($ctrl) ?>)</small>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary px-4 btn-lg" onclick="document.forms[0].submit();"><i class="fa-solid fa-floppy-disk me-2"></i>Save Role</button>
    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary btn-lg">Cancel</a>
</div>
<?= $this->Form->end() ?>
