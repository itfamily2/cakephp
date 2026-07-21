<?php
/**
 * Auto-Redesigned Form for AJAX Modal
 */
$this->Form->setTemplates([
    'inputContainer' => '<div class="mb-3">{{content}}</div>',
    'input' => '<input type="{{type}}" name="{{name}}" class="form-control"{{attrs}}/>',
    'select' => '<select name="{{name}}" class="form-select"{{attrs}}>{{content}}</select>',
    'textarea' => '<textarea name="{{name}}" class="form-control"{{attrs}}>{{value}}</textarea>',
    'checkboxFormGroup' => '<div class="form-check mb-3">{{label}}</div>',
    'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}" class="form-check-input"{{attrs}}>',
    'label' => '<label class="form-label fw-bold small text-muted"{{attrs}}>{{text}}</label>'
]);
?>
<div class='p-1'>
<?= $this->Form->create($auditLog) ?>
<?php
echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                    echo $this->Form->control('table_name');
                    echo $this->Form->control('row_id');
                    echo $this->Form->control('action');
                    echo $this->Form->control('old_values');
                    echo $this->Form->control('new_values');
                    echo $this->Form->control('ip_address');
?>

<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
    <?= $this->Form->button('<i class="fa-solid fa-floppy-disk me-1"></i> ' . __('Save Changes'), ['class' => 'btn btn-primary', 'escapeTitle' => false]) ?>
</div>
<?= $this->Form->end() ?>
</div>
