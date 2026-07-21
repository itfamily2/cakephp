import os, glob, re

template_dirs = ['/var/www/cakephp/templates', '/var/www/cakephp/plugins/UserManager/templates']

def process_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    # Regex to find $this->Html->link(...) for view and edit and add ajax-modal-link
    # Look for 'class' => 'btn btn-sm btn-outline-primary' and add ajax-modal-link
    
    new_content = re.sub(
        r"('class'\s*=>\s*'[a-zA-Z0-9\-\s]+?)(')", 
        lambda m: m.group(1) + (" ajax-modal-link" if "ajax-modal-link" not in m.group(1) and ("btn-outline-primary" in m.group(1) or "btn-outline-secondary" in m.group(1)) else "") + m.group(2), 
        content
    )
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print("Updated:", filepath)

for tdir in template_dirs:
    for root, dirs, files in os.walk(tdir):
        for file in files:
            if file == 'index.php':
                process_file(os.path.join(root, file))
