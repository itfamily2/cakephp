import os, glob, re

controller_dirs = ['/var/www/cakephp/src/Controller', '/var/www/cakephp/plugins/UserManager/src/Controller']

# This script finds the `return $this->redirect(['action' => 'index']);` line in edit/add methods 
# and injects the AJAX json response right before it.

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

    # Find the success redirect in add/edit methods.
    # Usually it looks like:
    # $this->Notification->success(__('...'));
    # return $this->redirect(['action' => 'index']);
    
    # We want to replace it only if it doesn't already have the ajax check.
    if "withType('application/json')" in content:
        return # Already patched or manually handled
        
    pattern = re.compile(r"(\$this->Notification->success\([^;]+;\s+return\s+\$this->redirect\(\['action'\s*=>\s*'index'\]\);\s*\})", re.MULTILINE)
    
    def repl(m):
        return patch_code + "\n" + m.group(1)
        
    new_content = pattern.sub(repl, content)
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print("Updated:", filepath)

for cdir in controller_dirs:
    for root, dirs, files in os.walk(cdir):
        for file in files:
            if file.endswith('Controller.php'):
                process_controller(os.path.join(root, file))
