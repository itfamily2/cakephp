<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ScheduledEmail> $scheduledEmails
 */
?>
<div class="scheduledEmails index content">
    <?= $this->Html->link(__('New Scheduled Email'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Scheduled Emails') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('email_template_id') ?></th>
                    <th><?= $this->Paginator->sort('email_signature_id') ?></th>
                    <th><?= $this->Paginator->sort('recipient_email') ?></th>
                    <th><?= $this->Paginator->sort('subject') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th><?= $this->Paginator->sort('scheduled_time') ?></th>
                    <th><?= $this->Paginator->sort('sent_time') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scheduledEmails as $scheduledEmail): ?>
                <tr>
                    <td><?= $this->Number->format($scheduledEmail->id) ?></td>
                    <td><?= $scheduledEmail->hasValue('email_template') ? $this->Html->link($scheduledEmail->email_template->name, ['controller' => 'EmailTemplates', 'action' => 'view', $scheduledEmail->email_template->id]) : '' ?></td>
                    <td><?= $scheduledEmail->hasValue('email_signature') ? $this->Html->link($scheduledEmail->email_signature->name, ['controller' => 'EmailSignatures', 'action' => 'view', $scheduledEmail->email_signature->id]) : '' ?></td>
                    <td><?= h($scheduledEmail->recipient_email) ?></td>
                    <td><?= h($scheduledEmail->subject) ?></td>
                    <td><?= h($scheduledEmail->status) ?></td>
                    <td><?= h($scheduledEmail->scheduled_time) ?></td>
                    <td><?= h($scheduledEmail->sent_time) ?></td>
                    <td><?= h($scheduledEmail->created) ?></td>
                    <td><?= h($scheduledEmail->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $scheduledEmail->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $scheduledEmail->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $scheduledEmail->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $scheduledEmail->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>