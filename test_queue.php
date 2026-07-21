<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\Queue\QueueManager;

QueueManager::push(\App\Job\OrderEmailJob::class, ['order_id' => 2]);
echo "Job pushed successfully!\n";
