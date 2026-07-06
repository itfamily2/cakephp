<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScheduledEmail $scheduledEmail
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Scheduled Email'), ['action' => 'edit', $scheduledEmail->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Scheduled Email'), ['action' => 'delete', $scheduledEmail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $scheduledEmail->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Scheduled Emails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Scheduled Email'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="scheduledEmails view content">
            <h3><?= h($scheduledEmail->recipient_email) ?></h3>
            <table>
                <tr>
                    <th><?= __('Email Template') ?></th>
                    <td><?= $scheduledEmail->hasValue('email_template') ? $this->Html->link($scheduledEmail->email_template->name, ['controller' => 'EmailTemplates', 'action' => 'view', $scheduledEmail->email_template->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Email Signature') ?></th>
                    <td><?= $scheduledEmail->hasValue('email_signature') ? $this->Html->link($scheduledEmail->email_signature->name, ['controller' => 'EmailSignatures', 'action' => 'view', $scheduledEmail->email_signature->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Recipient Email') ?></th>
                    <td><?= h($scheduledEmail->recipient_email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subject') ?></th>
                    <td><?= h($scheduledEmail->subject) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($scheduledEmail->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($scheduledEmail->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Scheduled Time') ?></th>
                    <td><?= h($scheduledEmail->scheduled_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sent Time') ?></th>
                    <td><?= h($scheduledEmail->sent_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($scheduledEmail->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($scheduledEmail->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Body') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($scheduledEmail->body)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>