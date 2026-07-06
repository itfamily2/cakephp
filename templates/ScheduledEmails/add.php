<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScheduledEmail $scheduledEmail
 * @var \Cake\Collection\CollectionInterface|string[] $emailTemplates
 * @var \Cake\Collection\CollectionInterface|string[] $emailSignatures
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Scheduled Emails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="scheduledEmails form content">
            <?= $this->Form->create($scheduledEmail) ?>
            <fieldset>
                <legend><?= __('Add Scheduled Email') ?></legend>
                <?php
                    echo $this->Form->control('email_template_id', ['options' => $emailTemplates, 'empty' => true]);
                    echo $this->Form->control('email_signature_id', ['options' => $emailSignatures, 'empty' => true]);
                    echo $this->Form->control('recipient_email');
                    echo $this->Form->control('subject');
                    echo $this->Form->control('body');
                    echo $this->Form->control('status');
                    echo $this->Form->control('scheduled_time', ['empty' => true]);
                    echo $this->Form->control('sent_time', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
