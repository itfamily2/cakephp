<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SentEmail $sentEmail
 * @var \Cake\Collection\CollectionInterface|string[] $emailTemplates
 * @var \Cake\Collection\CollectionInterface|string[] $emailSignatures
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Sent Emails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="sentEmails form content">
            <?= $this->Form->create($sentEmail) ?>
            <fieldset>
                <legend><?= __('Add Sent Email') ?></legend>
                <?php
                    echo $this->Form->control('email_template_id', ['options' => $emailTemplates, 'empty' => true]);
                    echo $this->Form->control('email_signature_id', ['options' => $emailSignatures, 'empty' => true]);
                    echo $this->Form->control('recipient_email');
                    echo $this->Form->control('subject');
                    echo $this->Form->control('body');
                    echo $this->Form->control('sent_time');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
