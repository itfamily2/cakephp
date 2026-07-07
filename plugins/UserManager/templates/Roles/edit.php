<?php $this->assign('title', 'Edit Role'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-user-shield text-primary me-2"></i>Edit <?= h($role->name) ?> Role</h4>
        <p class="text-muted mb-0">Update role details and bulk-assign module permissions.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'view', $role->id]) ?>" class="btn btn-outline-info bg-white">
            <i class="fa-solid fa-eye me-2"></i>View
        </a>
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-2"></i>Delete',
            ['action' => 'delete', $role->id],
            ['confirm' => __('Are you sure you want to delete # {0}?', $role->id), 'class' => 'btn btn-outline-danger bg-white', 'escape' => false]
        ) ?>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary bg-white">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-list text-info me-2"></i>Basic Details</h6>
            </div>
            <div class="card-body p-4">
                <?= $this->Form->create($role) ?>
                
                <div class="mb-3">
                    <?= $this->Form->control('name', [
                        'class' => 'form-control text-dark',
                        'label' => ['class' => 'form-label text-muted small fw-bold']
                    ]) ?>
                </div>
                
                <div class="mb-3">
                    <?= $this->Form->control('description', [
                        'class' => 'form-control text-dark',
                        'label' => ['class' => 'form-label text-muted small fw-bold'],
                        'type' => 'textarea',
                        'rows' => 4
                    ]) ?>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-cubes text-warning me-2"></i>Module Permissions</h6>
                <a href="<?= $this->Url->build(['controller' => 'Permissions', 'action' => 'index', '?' => ['role_id' => $role->id]]) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-sliders me-1"></i> Advanced Rules
                </a>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-4">Select the core modules this role should have full access to. This automatically assigns a wildcard (<code>*</code>) permission for the selected controller.</p>
                
                <div class="row g-3">
                    <?php foreach ($controllersList ?? [] as $ctrl => $label): ?>
                        <?php $isChecked = in_array($ctrl, $currentControllers ?? []); ?>
                        <div class="col-sm-6">
                            <div class="form-check p-2 rounded border transition-all <?= $isChecked ? 'bg-primary-subtle border-primary' : 'bg-light border-light' ?>" style="transition: all 0.2s;">
                                <input class="form-check-input ms-2 me-3 mt-2" type="checkbox" name="selected_controllers[]" value="<?= h($ctrl) ?>" id="ctrl_<?= h($ctrl) ?>" <?= $isChecked ? 'checked' : '' ?> style="transform: scale(1.2); cursor: pointer;">
                                <label class="form-check-label text-dark d-block w-100" for="ctrl_<?= h($ctrl) ?>" style="cursor: pointer;">
                                    <span class="fw-bold d-block"><?= h($label) ?></span>
                                    <small class="text-muted font-monospace" style="font-size: 0.75rem;"><?= h($ctrl) ?></small>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm" onclick="document.forms[0].submit();"><i class="fa-solid fa-floppy-disk me-2"></i>Save Role</button>
    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary py-2 px-4 bg-white shadow-sm">Cancel</a>
</div>
<?= $this->Form->end() ?>
