<?php $this->assign('title', 'Add Permission'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-key text-info me-2"></i>Add Permission Rule</h4>
        <p class="text-muted mb-0">Define access rules for controllers and actions.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Back to Permissions
    </a>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="glass-card p-4">
            <?= $this->Form->create($permission ?? null) ?>
            
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
                <div class="form-text text-muted small mt-1">Provide either a Role OR a Group to assign this permission.</div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('controller', [
                        'class' => 'form-control border-secondary bg-transparent text-white',
                        'label' => ['class' => 'form-label text-muted small'],
                        'placeholder' => 'e.g. Users',
                        'required' => true
                    ]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('action', [
                        'class' => 'form-control border-secondary bg-transparent text-white',
                        'label' => ['class' => 'form-label text-muted small'],
                        'placeholder' => 'e.g. index, view, *',
                        'required' => true
                    ]) ?>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="form-check form-switch">
                    <?= $this->Form->checkbox('allowed', [
                        'class' => 'form-check-input',
                        'id' => 'allowed',
                        'default' => true
                    ]) ?>
                    <label class="form-check-label text-white" for="allowed">
                        Allow Access
                        <span class="d-block text-muted small mt-1">If unchecked, this acts as an explicit DENY rule.</span>
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2 pt-3 border-top" style="border-color:var(--border-color)!important;">
                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-2"></i>Save Rule</button>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
