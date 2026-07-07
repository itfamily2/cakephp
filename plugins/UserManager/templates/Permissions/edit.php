<?php $this->assign('title', 'Edit Permission'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Permission Rule</h4>
        <p class="text-muted mb-0">Modify access control settings.</p>
    </div>
    <div class="d-flex gap-2">
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-2"></i>Delete',
            ['action' => 'delete', $permission->id],
            ['confirm' => __('Are you sure you want to delete this rule?'), 'class' => 'btn btn-danger', 'escape' => false]
        ) ?>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary bg-white">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-sliders text-info me-2"></i>Permission Configuration</h6>
            </div>
            <div class="card-body p-4">
                <?= $this->Form->create($permission) ?>
                
                <div class="mb-3">
                    <?= $this->Form->control('role_id', [
                        'options' => $roles ?? [],
                        'empty' => '(Select Role)',
                        'class' => 'form-select text-dark',
                        'label' => ['class' => 'form-label text-muted small fw-bold']
                    ]) ?>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('group_id', [
                        'options' => $groups ?? [],
                        'empty' => '(Select Group)',
                        'class' => 'form-select text-dark',
                        'label' => ['class' => 'form-label text-muted small fw-bold']
                    ]) ?>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?= $this->Form->control('controller', [
                            'class' => 'form-control text-dark font-monospace',
                            'label' => ['class' => 'form-label text-muted small fw-bold'],
                            'required' => true,
                            'placeholder' => 'e.g. Users'
                        ]) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <?= $this->Form->control('action', [
                            'class' => 'form-control text-dark font-monospace',
                            'label' => ['class' => 'form-label text-muted small fw-bold'],
                            'required' => true,
                            'placeholder' => 'e.g. index, add, *'
                        ]) ?>
                    </div>
                </div>
                
                <div class="mb-4 pt-2">
                    <div class="form-check form-switch form-check-lg p-3 rounded bg-light border">
                        <?= $this->Form->checkbox('allowed', [
                            'class' => 'form-check-input ms-0 me-3 mt-1',
                            'id' => 'allowed',
                            'style' => 'width: 40px; height: 20px; cursor: pointer;'
                        ]) ?>
                        <label class="form-check-label text-dark fw-bold" for="allowed" style="cursor: pointer; padding-top:2px;">
                            Allow Access to this Endpoint
                        </label>
                        <div class="text-muted small mt-1 ms-5 ps-1">If disabled, any role/group matching this rule will be explicitly denied.</div>
                    </div>
                </div>
                
                <div class="d-flex gap-2 pt-3 border-top mt-4">
                    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
                
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
