import os, glob, re

template_dirs = ['/var/www/cakephp/templates', '/var/www/cakephp/plugins/UserManager/templates']

def process_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    def repl(m):
        full_match = m.group(0)
        class_match = re.search(r'class="([^"]+)"', full_match)
        if class_match:
            classes = class_match.group(1)
            if 'ajax-modal-link' not in classes:
                new_classes = classes + ' ajax-modal-link'
                full_match = full_match.replace(f'class="{classes}"', f'class="{new_classes}"')
        return full_match

    # Match <a href="<?= $this->Url->build(['action' => '...']) ?>" class="...">
    new_content = re.sub(r'<a[^>]+href="<\?=\s*\$this->Url->build\(\[\s*\'action\'\s*=>\s*\'(?:view|edit|add)\'[^>]+>', repl, content)
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print("Updated raw A tags in:", filepath)

for tdir in template_dirs:
    for root, dirs, files in os.walk(tdir):
        for file in files:
            if file.endswith('.php'):
                process_file(os.path.join(root, file))
