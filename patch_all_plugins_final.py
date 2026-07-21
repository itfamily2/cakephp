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

    # If it already returns JSON, skip it to avoid duplicates
    if "withType('application/json')" in content or "$this->viewBuilder()->setClassName('Json')" in content:
        # But wait, some controllers have it for edit() but not add()
        pass

    # We want to replace `return $this->redirect(['action' => 'index']);`
    # if it's immediately after a success notification.
    # A more robust regex:
    pattern = re.compile(r"(\$this->[a-zA-Z0-9_]+->success\([^;]+\);)\s*(return\s+\$this->redirect\(\['action'\s*=>\s*'index'\]\);)", re.MULTILINE)
    
    def repl(m):
        # Prevent double patching
        # The easiest way is to check if the patch code is already in the file right before this match
        return m.group(1) + patch_code + "\n                " + m.group(2)
        
    new_content = pattern.sub(repl, content)
    
    # Clean up double patches just in case
    new_content = new_content.replace(patch_code + patch_code, patch_code)
    
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
