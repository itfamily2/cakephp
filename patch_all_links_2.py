import os, glob, re

template_dirs = ['/var/www/cakephp/templates', '/var/www/cakephp/plugins/UserManager/templates']

def process_file(filepath):
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
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print("Updated link classes in:", filepath)

for tdir in template_dirs:
    for root, dirs, files in os.walk(tdir):
        for file in files:
            if file.endswith('.php'):
                process_file(os.path.join(root, file))
