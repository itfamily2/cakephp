<?php
$dir = __DIR__ . '/../plugins/LoadTesting/src/Controller/';
$files = glob($dir . '*Controller.php');

foreach ($files as $file) {
    if (basename($file) === 'AppController.php') continue;
    
    $className = basename($file, '.php');
    
    $content = <<<PHP
<?php
declare(strict_types=1);

namespace LoadTesting\Controller;

use LoadTesting\Controller\AppController;

class {$className} extends AppController
{
    protected ?string \$defaultTable = null;

    public function index()
    {
        if (\$this->components()->has('Authorization')) {
            \$this->Authorization->skipAuthorization();
        }
    }
}
PHP;
    file_put_contents($file, $content);
}
echo "Cleaned all controllers.\n";
