<?php
declare(strict_types=1);

namespace LoadTesting\Controller;

use LoadTesting\Controller\AppController;

class DashboardController extends AppController
{
    protected ?string $defaultTable = null;

    public function index()
    {
        // Bypass both Authorization and Authentication for this internal tool
        if ($this->components()->has('Authorization')) {
            $this->Authorization->skipAuthorization();
        }
        if ($this->components()->has('Authentication')) {
            $this->Authentication->addUnauthenticatedActions(['index']);
        }

        // Database Health
        $dbHealth = 'Online';
        try {
            $connection = \Cake\Datasource\ConnectionManager::get('default');
            $connection->execute('SELECT 1')->fetchAll();
        } catch (\Exception $e) {
            $dbHealth = 'Offline';
        }

        // CPU Usage
        $cpuUsage = 0;
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            $cpuUsage = round($load[0] * 10, 1);
            if ($cpuUsage > 100) $cpuUsage = 100;
        }

        // Memory Usage
        $memUsage = 0;
        if (file_exists('/proc/meminfo')) {
            $meminfo = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+)\s+kB/', $meminfo, $total);
            preg_match('/MemAvailable:\s+(\d+)\s+kB/', $meminfo, $avail);
            if (isset($total[1]) && isset($avail[1])) {
                $used = $total[1] - $avail[1];
                $memUsage = round(($used / $total[1]) * 100, 1);
            }
        }

        // Disk Usage
        $diskUsage = 0;
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        if ($diskTotal > 0) {
            $diskUsage = round((($diskTotal - $diskFree) / $diskTotal) * 100, 1);
        }

        // Recent Errors (Grouped & Unique)
        $recentErrors = [];
        $logPath = LOGS . 'error.log';
        if (file_exists($logPath)) {
            // Read last 200 lines to get a good sample
            $lines = array_slice(file($logPath), -200);
            $errorCounts = [];
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                // Skip stack trace lines (typically start with # or are file paths)
                if (strpos($line, '#') === 0 || preg_match('/\.php(:\d+)?$/', $line)) {
                    continue;
                }
                
                // Try to extract the core error message (ignoring timestamps)
                // Format usually is: 2026-07-21 15:45:12 Error: [Exception] message
                if (preg_match('/(?:Error|Warning|Notice):\s*(.*)/', $line, $matches)) {
                    $msg = $matches[1];
                    // Shorten very long messages
                    if (strlen($msg) > 100) $msg = substr($msg, 0, 97) . '...';
                    
                    if (!isset($errorCounts[$msg])) {
                        $errorCounts[$msg] = 0;
                    }
                    $errorCounts[$msg]++;
                }
            }
            
            // Sort by frequency (highest first)
            arsort($errorCounts);
            
            // Take top 5
            foreach (array_slice($errorCounts, 0, 5, true) as $msg => $count) {
                $recentErrors[] = [
                    'message' => $msg,
                    'count' => $count
                ];
            }
        }

        $this->set(compact('dbHealth', 'cpuUsage', 'memUsage', 'diskUsage', 'recentErrors'));
    }
}