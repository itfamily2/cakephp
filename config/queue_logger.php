<?php
\Cake\Log\Log::setConfig('queue', [
    'className' => \Cake\Log\Engine\FileLog::class,
    'path' => LOGS,
    'file' => 'queue',
    'scopes' => ['queue'],
    'levels' => ['info', 'warning', 'error'],
]);
