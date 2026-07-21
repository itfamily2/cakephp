import os, glob, re

template_dirs = ['/var/www/cakephp/templates', '/var/www/cakephp/plugins/UserManager/templates']

def process_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    # Find ANY Html->link that goes to an 'action' => 'view' or 'action' => 'edit' or 'action' => 'add'
    # and ensure its class string contains ajax-modal-link
    
    def repl(m):
        full_match = m.group(0)
        class_match = re.search(r"'class'\s*=>\s*'([^']+)'", full_match)
        if class_match:
            classes = class_match.group(1)
            if 'ajax-modal-link' not in classes:
                new_classes = classes + ' ajax-modal-link'
                full_match = full_match.replace(class_match.group(0), f"'class' => '{new_classes}'")
        else:
            # If it doesn't have a class attribute at all in the options array
            # we try to inject it before the closing bracket of the options array.
            # This is harder to regex safely.
            pass
            
        return full_match

    # Match Html->link containing 'action' => 'view' or 'edit' or 'add'
    new_content = re.sub(r"->Html->link\([^)]+'action'\s*=>\s*'(?:view|edit|add)'[^)]+\)", repl, content)
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print("Updated link classes in:", filepath)

for tdir in template_dirs:
    for root, dirs, files in os.walk(tdir):
        for file in files:
            if file == 'index.php':
                process_file(os.path.join(root, file))
