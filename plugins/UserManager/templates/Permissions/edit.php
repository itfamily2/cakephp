<?php $this->assign('title', 'Edit Permission'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Permission Rule</h4>
        <p class="text-muted mb-0">Modify access control settings.</p>
    </div>
    <div class="d-flex gap-2">
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-2"></i>Delete',
            ['action' => 'delete', $permission->id],
            ['confirm' => __('Are you sure you want to delete this rule?'), 'class' => 'btn btn-outline-danger', 'escape' => false]
        ) ?>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="glass-card p-4">
            <?= $this->Form->create($permission) ?>
            
            <div class="mb-3">
                <?= $this->Form->control('role_id', [
                    'options' => $roles ?? [],
                    'empty' => '(Select Role)',
                    'class' => 'form-select border-secondary bg-transparent text-white',
                    'label' => ['class' => 'form-label text-muted small']
                ]) ?>
            </div>

            <div class="mb-3">
                <?= $this->Form->control('group_id', [
                    'options' => $groups ?? [],
                    'empty' => '(Select Group)',
                    'class' => 'form-select border-secondary bg-transparent text-white',
                    'label' => ['class' => 'form-label text-muted small']
                ]) ?>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('controller', [
                        'class' => 'form-control border-secondary bg-transparent text-white',
                        'label' => ['class' => 'form-label text-muted small'],
                        'required' => true
                    ]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('action', [
                        'class' => 'form-control border-secondary bg-transparent text-white',
                        'label' => ['class' => 'form-label text-muted small'],
                        'required' => true
                    ]) ?>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="form-check form-switch">
                    <?= $this->Form->checkbox('allowed', [
                        'class' => 'form-check-input',
                        'id' => 'allowed'
                    ]) ?>
                    <label class="form-check-label text-white" for="allowed">
                        Allow Access
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2 pt-3 border-top" style="border-color:var(--border-color)!important;">
                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
