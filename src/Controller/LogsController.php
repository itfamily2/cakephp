<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

/**
 * LogsController — CakePHP 5 compatible (no deprecated Cake\Filesystem\Folder)
 * Uses native PHP filesystem functions instead.
 */
class LogsController extends AppController
{
    private string $logPath;

    public function initialize(): void
    {
        parent::initialize();
        $this->logPath = LOGS;
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();

        $logFiles = [];
        if (is_dir($this->logPath)) {
            foreach (glob($this->logPath . '*.log') as $filePath) {
                $fileName = basename($filePath);
                $logFiles[] = [
                    'name'     => $fileName,
                    'size'     => filesize($filePath),
                    'modified' => filemtime($filePath),
                    'lines'    => count(file($filePath)),
                ];
            }
        }

        // Sort by modified time, newest first
        usort($logFiles, fn($a, $b) => $b['modified'] <=> $a['modified']);

        $this->set(compact('logFiles'));
    }

    public function view(string $fileName): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();
        $fileName = basename($fileName);
        $filePath = $this->logPath . $fileName;

        if (!file_exists($filePath)) {
            $this->Notification->error(__('Log file not found.'));
            return $this->redirect(['action' => 'index']);
        }

        // Get last 200 lines for display
        $allLines = file($filePath, FILE_IGNORE_NEW_LINES);
        $content  = implode(PHP_EOL, array_slice($allLines, -200));
        $size     = filesize($filePath);
        $lines    = count($allLines);

        $this->set(compact('fileName', 'content', 'size', 'lines'));
        return null;
    }

    public function backup(string $fileName): ?\Cake\Http\Response
    {
        $fileName   = basename($fileName);
        $filePath   = $this->logPath . $fileName;

        if (!file_exists($filePath)) {
            $this->Notification->error(__('Log file not found.'));
            return $this->redirect(['action' => 'index']);
        }

        $backupName = pathinfo($fileName, PATHINFO_FILENAME) . '_backup_' . date('Ymd_His') . '.log';
        if (copy($filePath, $this->logPath . $backupName)) {
            $this->Notification->success(__('Backup created: {0}', $backupName));
        } else {
            $this->Notification->error(__('Failed to create backup.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function empty(string $fileName): ?\Cake\Http\Response
    {
        $fileName = basename($fileName);
        $filePath = $this->logPath . $fileName;

        if (!file_exists($filePath)) {
            $this->Notification->error(__('Log file not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if (file_put_contents($filePath, '') !== false) {
            $this->Notification->success(__('Log file cleared.'));
        } else {
            $this->Notification->error(__('Failed to clear log file.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function delete(string $fileName): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post', 'delete']);
        $fileName = basename($fileName);
        $filePath = $this->logPath . $fileName;

        if (!file_exists($filePath)) {
            $this->Notification->error(__('Log file not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if (unlink($filePath)) {
            $this->Notification->success(__('Log file deleted.'));
        } else {
            $this->Notification->error(__('Failed to delete log file.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
