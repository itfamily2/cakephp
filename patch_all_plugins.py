import os, glob, re

# 1. Update Controllers
controller_dirs = ['/var/www/cakephp/src/Controller'] + glob.glob('/var/www/cakephp/plugins/*/src/Controller')

patch_code = """
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }
"""

def process_controller(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    if "withType('application/json')" in content:
        return
        
    pattern = re.compile(r"(\$this->Notification->success\([^;]+;\s+return\s+\$this->redirect\(\['action'\s*=>\s*'index'\]\);\s*\})", re.MULTILINE)
    
    def repl(m):
        return patch_code + "\n" + m.group(1)
        
    new_content = pattern.sub(repl, content)
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print("Updated Controller:", filepath)

for cdir in controller_dirs:
    for root, dirs, files in os.walk(cdir):
        for file in files:
            if file.endswith('Controller.php'):
                process_controller(os.path.join(root, file))

# 2. Update Templates Links
template_dirs = ['/var/www/cakephp/templates'] + glob.glob('/var/www/cakephp/plugins/*/templates')

def process_links(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    def repl(m):
        full_match = m.group(0)
        class_match = re.search(r"'class'\s*=>\s*'([^']+)'", full_match)
        if class_match:
            classes = class_match.group(1)
            if 'ajax-modal-link' not in classes:
                new_classes = classes + ' ajax-modal-link'
                full_match = full_match.replace(class_match.group(0), f"'class' => '{new_classes}'")
        return full_match

    new_content = re.sub(r"->Html->link\([^)]+'action'\s*=>\s*'(?:view|edit|add)'[^)]+\)", repl, content)
    
    # Also raw anchor tags
    def repl_raw(m):
        full_match = m.group(0)
        class_match = re.search(r'class="([^"]+)"', full_match)
        if class_match:
            classes = class_match.group(1)
            if 'ajax-modal-link' not in classes:
                new_classes = classes + ' ajax-modal-link'
                full_match = full_match.replace(f'class="{classes}"', f'class="{new_classes}"')
        return full_match

    new_content = re.sub(r'<a[^>]+href="<\?=\s*\$this->Url->build\(\[\s*\'action\'\s*=>\s*\'(?:view|edit|add)\'[^>]+>', repl_raw, new_content)

    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print("Updated Links:", filepath)

for tdir in template_dirs:
    for root, dirs, files in os.walk(tdir):
        for file in files:
            if file.endswith('.php'):
                process_links(os.path.join(root, file))

# 3. Redesign Forms
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

def process_form(filepath):
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
    print("Redesigned Form:", filepath)

# 4. Redesign Views
view_templates_php = """
<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
"""

def process_view(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    if "Auto-Redesigned View" in content:
        return

    table_rows = re.findall(r'(<tr>\s*<th>.*?</th>\s*<td>.*?</td>\s*</tr>)', content, re.DOTALL)
    
    if not table_rows:
        return

    new_content = "<!-- Auto-Redesigned View -->\n" + view_templates_php
    for row in table_rows:
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
    print("Redesigned View:", filepath)

for tdir in template_dirs:
    for root, dirs, files in os.walk(tdir):
        for file in files:
            filepath = os.path.join(root, file)
            if file in ['add.php', 'edit.php']:
                process_form(filepath)
            elif file == 'view.php':
                process_view(filepath)
