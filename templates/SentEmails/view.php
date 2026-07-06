<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SentEmail $sentEmail
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Sent Email'), ['action' => 'edit', $sentEmail->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Sent Email'), ['action' => 'delete', $sentEmail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sentEmail->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Sent Emails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Sent Email'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="sentEmails view content">
            <h3><?= h($sentEmail->recipient_email) ?></h3>
            <table>
                <tr>
                    <th><?= __('Email Template') ?></th>
                    <td><?= $sentEmail->hasValue('email_template') ? $this->Html->link($sentEmail->email_template->name, ['controller' => 'EmailTemplates', 'action' => 'view', $sentEmail->email_template->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Email Signature') ?></th>
                    <td><?= $sentEmail->hasValue('email_signature') ? $this->Html->link($sentEmail->email_signature->name, ['controller' => 'EmailSignatures', 'action' => 'view', $sentEmail->email_signature->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Recipient Email') ?></th>
                    <td><?= h($sentEmail->recipient_email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subject') ?></th>
                    <td><?= h($sentEmail->subject) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($sentEmail->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sent Time') ?></th>
                    <td><?= h($sentEmail->sent_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($sentEmail->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($sentEmail->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Body') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($sentEmail->body)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>