import os, re

template_dir = '/var/www/cakephp/templates'

form_templates_php = """<?php
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
?>"""

def process_form_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    if "Auto-Redesigned Form" in content:
        return

    create_match = re.search(r'<\?=\s*\$this->Form->create\([^)]*\)\s*\?>', content)
    if not create_match:
        return
        
    create_str = create_match.group(0)
    
    controls_match = re.search(r'<\?php\s+(echo\s+\$this->Form->control.*?;)\s*\?>', content, re.DOTALL)
    if not controls_match:
        return
        
    controls_code = controls_match.group(1)
    
    new_content = form_templates_php + "\n<div class='p-1'>\n" + create_str + "\n"
    new_content += "<?php\n" + controls_code + "\n?>\n"
    
    new_content += """
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
    <?= $this->Form->button('<i class="fa-solid fa-floppy-disk me-1"></i> ' . __('Save Changes'), ['class' => 'btn btn-primary', 'escapeTitle' => false]) ?>
</div>
<?= $this->Form->end() ?>
</div>
"""
    with open(filepath, 'w') as f:
        f.write(new_content)
    print("Redesigned form:", filepath)

# Run for Add and Edit
for root, dirs, files in os.walk(template_dir):
    for file in files:
        if file in ['add.php', 'edit.php']:
            process_form_file(os.path.join(root, file))


# NOW DO view.php
view_templates_php = """
<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
"""

def process_view_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    if "Auto-Redesigned View" in content:
        return

    # Extract all th / td pairs
    # The scaffold creates tables like:
    # <tr>
    #     <th><?= __('Name') ?></th>
    #     <td><?= h($entity->name) ?></td>
    # </tr>
    table_rows = re.findall(r'(<tr>\s*<th>.*?</th>\s*<td>.*?</td>\s*</tr>)', content, re.DOTALL)
    
    if not table_rows:
        return

    new_content = "<!-- Auto-Redesigned View -->\n" + view_templates_php
    for row in table_rows:
        # replace th with a styled th
        row = re.sub(r'<th>', '<th class="bg-light text-muted w-25">', row)
        new_content += row + "\n"
        
    new_content += """
    </table>
</div>
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
"""
    with open(filepath, 'w') as f:
        f.write(new_content)
    print("Redesigned view:", filepath)

for root, dirs, files in os.walk(template_dir):
    for file in files:
        if file == 'view.php':
            process_view_file(os.path.join(root, file))

