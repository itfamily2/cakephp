<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

class LogsController extends AppController
{
    private string $logPath;

    public function initialize(): void
    {
        parent::initialize();
        $this->logPath = LOGS;
    }

    /**
     * List all log files
     */
    public function index()
    {
        $folder = new Folder($this->logPath);
        $files = $folder->find('.*\.log');
        
        $logFiles = [];
        foreach ($files as $fileName) {
            $file = new File($this->logPath . $fileName);
            $logFiles[] = [
                'name' => $fileName,
                'size' => $file->size(),
                'modified' => $file->lastChange(),
            ];
        }

        $this->set(compact('logFiles'));
    }

    /**
     * View/Edit a particular log file
     */
    public function view(string $fileName)
    {
        $fileName = basename($fileName);
        $filePath = $this->logPath . $fileName;

        if (!file_exists($filePath)) {
            $this->Notification->error(__('Log file not found.'));
            return $this->redirect(['action' => 'index']);
        }

        $file = new File($filePath);
        
        if ($this->request->is(['post', 'put'])) {
            $content = $this->request->getData('content');
            if ($file->write($content)) {
                $this->Notification->success(__('Log file updated successfully.'));
                return $this->redirect(['action' => 'view', $fileName]);
            }
            $this->Notification->error(__('Failed to update log file.'));
        }

        $content = $file->read();
        $size = $file->size();

        $this->set(compact('fileName', 'content', 'size'));
    }

    /**
     * Create backup of a log file
     */
    public function backup(string $fileName)
    {
        $fileName = basename($fileName);
        $filePath = $this->logPath . $fileName;

        if (!file_exists($filePath)) {
            $this->Notification->error(__('Log file not found.'));
            return $this->redirect(['action' => 'index']);
        }

        $file = new File($filePath);
        $backupName = pathinfo($fileName, PATHINFO_FILENAME) . '_backup_' . date('Ymd_His') . '.log';
        
        if ($file->copy($this->logPath . $backupName)) {
            $this->Notification->success(__('Backup created successfully as {0}.', $backupName));
        } else {
            $this->Notification->error(__('Failed to create backup.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Empty the log file
     */
    public function empty(string $fileName)
    {
        $fileName = basename($fileName);
        $filePath = $this->logPath . $fileName;

        if (!file_exists($filePath)) {
            $this->Notification->error(__('Log file not found.'));
            return $this->redirect(['action' => 'index']);
        }

        $file = new File($filePath);
        if ($file->write('')) {
            $this->Notification->success(__('Log file emptied successfully.'));
        } else {
            $this->Notification->error(__('Failed to empty log file.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete a log file
     */
    public function delete(string $fileName)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fileName = basename($fileName);
        $filePath = $this->logPath . $fileName;

        if (!file_exists($filePath)) {
            $this->Notification->error(__('Log file not found.'));
            return $this->redirect(['action' => 'index']);
        }

        $file = new File($filePath);
        if ($file->delete()) {
            $this->Notification->success(__('Log file deleted successfully.'));
        } else {
            $this->Notification->error(__('Failed to delete log file.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
