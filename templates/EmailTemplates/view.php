<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmailTemplate $emailTemplate
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Email Template'), ['action' => 'edit', $emailTemplate->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Email Template'), ['action' => 'delete', $emailTemplate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $emailTemplate->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Email Templates'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Email Template'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="emailTemplates view content">
            <h3><?= h($emailTemplate->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($emailTemplate->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subject') ?></th>
                    <td><?= h($emailTemplate->subject) ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $emailTemplate->hasValue('user') ? $this->Html->link($emailTemplate->user->username, ['controller' => 'Users', 'action' => 'view', $emailTemplate->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($emailTemplate->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($emailTemplate->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($emailTemplate->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Body') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($emailTemplate->body)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Scheduled Emails') ?></h4>
                <?php if (!empty($emailTemplate->scheduled_emails)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Email Signature Id') ?></th>
                            <th><?= __('Recipient Email') ?></th>
                            <th><?= __('Subject') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Scheduled Time') ?></th>
                            <th><?= __('Sent Time') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($emailTemplate->scheduled_emails as $scheduledEmail) : ?>
                        <tr>
                            <td><?= h($scheduledEmail->id) ?></td>
                            <td><?= h($scheduledEmail->email_signature_id) ?></td>
                            <td><?= h($scheduledEmail->recipient_email) ?></td>
                            <td><?= h($scheduledEmail->subject) ?></td>
                            <td><?= h($scheduledEmail->body) ?></td>
                            <td><?= h($scheduledEmail->status) ?></td>
                            <td><?= h($scheduledEmail->scheduled_time) ?></td>
                            <td><?= h($scheduledEmail->sent_time) ?></td>
                            <td><?= h($scheduledEmail->created) ?></td>
                            <td><?= h($scheduledEmail->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'ScheduledEmails', 'action' => 'view', $scheduledEmail->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'ScheduledEmails', 'action' => 'edit', $scheduledEmail->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'ScheduledEmails', 'action' => 'delete', $scheduledEmail->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $scheduledEmail->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Sent Emails') ?></h4>
                <?php if (!empty($emailTemplate->sent_emails)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Email Signature Id') ?></th>
                            <th><?= __('Recipient Email') ?></th>
                            <th><?= __('Subject') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Sent Time') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($emailTemplate->sent_emails as $sentEmail) : ?>
                        <tr>
                            <td><?= h($sentEmail->id) ?></td>
                            <td><?= h($sentEmail->email_signature_id) ?></td>
                            <td><?= h($sentEmail->recipient_email) ?></td>
                            <td><?= h($sentEmail->subject) ?></td>
                            <td><?= h($sentEmail->body) ?></td>
                            <td><?= h($sentEmail->sent_time) ?></td>
                            <td><?= h($sentEmail->created) ?></td>
                            <td><?= h($sentEmail->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'SentEmails', 'action' => 'view', $sentEmail->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'SentEmails', 'action' => 'edit', $sentEmail->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'SentEmails', 'action' => 'delete', $sentEmail->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $sentEmail->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>