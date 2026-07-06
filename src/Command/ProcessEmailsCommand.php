<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Cake\I18n\DateTime;

/**
 * ProcessEmails command.
 */
class ProcessEmailsCommand extends Command
{
    /**
     * The name of this command.
     *
     * @var string
     */
    protected string $name = 'cake process_emails';

    /**
     * Get the default command name.
     *
     * @return string
     */
    public static function defaultName(): string
    {
        return 'process_emails';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Process queued emails in the scheduled_emails table.';
    }

    /**
     * Hook method for defining this command's option parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return parent::buildOptionParser($parser)
            ->setDescription(static::getDescription());
    }

    /**
     * Implement this method with your command's logic.
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $scheduledTable = TableRegistry::getTableLocator()->get('ScheduledEmails');
        $sentTable = TableRegistry::getTableLocator()->get('SentEmails');

        // Find all pending emails that are scheduled for now or in the past
        $emails = $scheduledTable->find()
            ->where([
                'status' => 'pending',
                'OR' => [
                    'scheduled_time IS' => null,
                    'scheduled_time <=' => DateTime::now()
                ]
            ])
            ->all();

        if ($emails->isEmpty()) {
            $io->out('No pending scheduled emails found.');
            return static::CODE_SUCCESS;
        }

        $io->out(sprintf('Found %d pending email(s) to process.', $emails->count()));

        foreach ($emails as $email) {
            $io->out(sprintf('Sending email to %s...', $email->recipient_email));

            try {
                // Setup the mailer
                $mailer = new Mailer('default');
                $mailer->setTo($email->recipient_email)
                    ->setSubject($email->subject)
                    ->deliver($email->body);

                // Update scheduled email status to sent
                $email->status = 'sent';
                $email->sent_time = DateTime::now();
                $scheduledTable->save($email);

                // Log to sent_emails table
                $sentEmail = $sentTable->newEmptyEntity();
                $sentEmail->email_template_id = $email->email_template_id;
                $sentEmail->email_signature_id = $email->email_signature_id;
                $sentEmail->recipient_email = $email->recipient_email;
                $sentEmail->subject = $email->subject;
                $sentEmail->body = $email->body;
                $sentEmail->sent_time = DateTime::now();
                $sentTable->save($sentEmail);

                $io->success(sprintf('Successfully sent email to %s', $email->recipient_email));
            } catch (\Exception $e) {
                // If mailer fails (e.g. no SMTP connection), catch exception and update scheduled email status
                $email->status = 'failed';
                $scheduledTable->save($email);

                $io->error(sprintf('Failed to send email to %s. Error: %s', $email->recipient_email, $e->getMessage()));
            }
        }

        $io->out('Email processing complete.');
        return static::CODE_SUCCESS;
    }
}
