<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Utility\Text;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

/**
 * Phase 11 - UploadComponent
 * 
 * Handles file uploads, generating unique filenames and moving files
 * to specified directories securely.
 */
class UploadComponent extends Component
{
    protected array $_defaultConfig = [
        'uploadDir' => WWW_ROOT . 'uploads' . DS,
        'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'maxSize' => 5242880, // 5MB
    ];

    /**
     * Upload a file.
     *
     * @param \Psr\Http\Message\UploadedFileInterface $file The uploaded file object
     * @param string $subDir Subdirectory within the main upload dir (e.g. 'profiles')
     * @return string The generated filename
     * @throws \RuntimeException If upload fails or validation fails
     */
    public function upload(UploadedFileInterface $file, string $subDir = ''): string
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new RuntimeException('File upload error code: ' . $file->getError());
        }

        if ($file->getSize() > $this->getConfig('maxSize')) {
            throw new RuntimeException('File exceeds maximum allowed size.');
        }

        $filename = $file->getClientFilename();
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $this->getConfig('allowedExtensions'))) {
            throw new RuntimeException('File extension not allowed.');
        }

        // Generate unique filename
        $newFilename = Text::uuid() . '.' . $ext;

        $targetPath = $this->getConfig('uploadDir') . $subDir;
        if (!empty($subDir) && !str_ends_with($targetPath, DS)) {
            $targetPath .= DS;
        }

        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
        }

        $file->moveTo($targetPath . $newFilename);

        return $newFilename;
    }
}
