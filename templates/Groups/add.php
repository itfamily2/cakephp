<?php $this->assign('title', 'Add Group'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-users-rectangle text-info me-2"></i>Create New Group</h4>
        <p class="text-muted mb-0">Define a new user group for access control and organization.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Back to Groups
    </a>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="glass-card p-4">
            <?= $this->Form->create($group) ?>
            
            <div class="mb-3">
                <?= $this->Form->control('name', [
                    'class' => 'form-control border-secondary bg-transparent text-white',
                    'label' => ['class' => 'form-label text-muted small'],
                    'placeholder' => 'e.g. Content Editors',
                    'required' => true
                ]) ?>
            </div>
            
            <div class="mb-4">
                <?= $this->Form->control('parent_id', [
                    'options' => $parentGroups,
                    'empty' => '(No Parent - Top Level Group)',
                    'class' => 'form-select border-secondary bg-transparent text-white',
                    'label' => ['class' => 'form-label text-muted small']
                ]) ?>
                <div class="form-text text-muted small mt-1">If selected, this group inherits properties from the parent group.</div>
            </div>
            
            <div class="mb-4">
                <div class="form-check form-switch">
                    <?= $this->Form->checkbox('registration_allowed', [
                        'class' => 'form-check-input',
                        'id' => 'registration_allowed'
                    ]) ?>
                    <label class="form-check-label text-white" for="registration_allowed">
                        Allow Public Registration
                        <span class="d-block text-muted small mt-1">If enabled, new users can select this group during sign-up.</span>
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2 pt-3 border-top" style="border-color:var(--border-color)!important;">
                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-2"></i>Save Group</button>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>
    <div class="col-md-4 col-lg-6">
        <div class="glass-card p-4 bg-primary bg-opacity-10 border-primary">
            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-circle-info me-2"></i>About Groups</h6>
            <p class="text-muted small mb-2">Groups provide an organizational layer above standard Roles. They are useful for:</p>
            <ul class="text-muted small mb-0 ps-3">
                <li class="mb-1">Multi-tenant segregation (e.g. separating users by Company/Department).</li>
                <li class="mb-1">Applying broad organizational permissions.</li>
                <li>Hierarchical structuring using the Parent Group feature.</li>
            </ul>
        </div>
    </div>
</div>
