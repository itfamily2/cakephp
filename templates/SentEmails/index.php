<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SentEmail> $sentEmails
 */
?>
<div class="sentEmails index content">
    <?= $this->Html->link(__('New Sent Email'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Sent Emails') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('email_template_id') ?></th>
                    <th><?= $this->Paginator->sort('email_signature_id') ?></th>
                    <th><?= $this->Paginator->sort('recipient_email') ?></th>
                    <th><?= $this->Paginator->sort('subject') ?></th>
                    <th><?= $this->Paginator->sort('sent_time') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sentEmails as $sentEmail): ?>
                <tr>
                    <td><?= $this->Number->format($sentEmail->id) ?></td>
                    <td><?= $sentEmail->hasValue('email_template') ? $this->Html->link($sentEmail->email_template->name, ['controller' => 'EmailTemplates', 'action' => 'view', $sentEmail->email_template->id]) : '' ?></td>
                    <td><?= $sentEmail->hasValue('email_signature') ? $this->Html->link($sentEmail->email_signature->name, ['controller' => 'EmailSignatures', 'action' => 'view', $sentEmail->email_signature->id]) : '' ?></td>
                    <td><?= h($sentEmail->recipient_email) ?></td>
                    <td><?= h($sentEmail->subject) ?></td>
                    <td><?= h($sentEmail->sent_time) ?></td>
                    <td><?= h($sentEmail->created) ?></td>
                    <td><?= h($sentEmail->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $sentEmail->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sentEmail->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $sentEmail->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $sentEmail->id),
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