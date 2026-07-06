<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Log\Log;

/**
 * Phase 18 - CLI Command (Queue Worker)
 * 
 * Processes background jobs from the `jobs` table.
 * Setup in cron: * * * * * cd /var/www/cakephp && bin/cake process_queue
 */
class ProcessQueueCommand extends Command
{
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Processes the background job queue.');
        $parser->addOption('queue', [
            'help' => 'The queue name to process',
            'default' => 'default',
        ]);
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $queueName = $args->getOption('queue');
        $io->out("<info>Starting queue processor for [{$queueName}] queue...</info>");

        $jobsTable = $this->fetchTable('Jobs');
        
        // Find one job that is available to run
        $job = $jobsTable->find()
            ->where(['queue' => $queueName])
            ->where(['available_at <=' => new \DateTime()])
            ->where(['reserved_at IS' => null])
            ->orderAsc('id')
            ->first();

        if (!$job) {
            $io->out('No jobs to process.');
            return self::CODE_SUCCESS;
        }

        // Lock the job
        $job->reserved_at = new \DateTime();
        $job->attempts += 1;
        $jobsTable->save($job);

        $io->out("Processing job ID: {$job->id}");

        try {
            // Simulated payload execution (e.g. sending email)
            $payload = json_decode($job->payload, true);
            $io->out("Executing task: " . ($payload['task'] ?? 'unknown'));
            
            // Sleep to simulate work
            sleep(1);
            
            // Success: Delete job
            $jobsTable->delete($job);
            $io->success("Job ID: {$job->id} processed successfully.");

        } catch (\Exception $e) {
            $io->error("Job ID: {$job->id} failed: " . $e->getMessage());
            Log::error("Queue Job Failed: " . $e->getMessage());
            
            // Move to failed jobs
            $failedTable = $this->fetchTable('FailedJobs');
            $failedJob = $failedTable->newEntity([
                'connection' => 'default',
                'queue' => $job->queue,
                'payload' => $job->payload,
                'exception' => $e->getMessage(),
                'failed_at' => new \DateTime(),
            ]);
            $failedTable->save($failedJob);
            $jobsTable->delete($job);
            
            return self::CODE_ERROR;
        }

        return self::CODE_SUCCESS;
    }
}
