<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactEnquiry $contactEnquiry
 * @var \Cake\Collection\CollectionInterface|string[] $assignedStaffs
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Contact Enquiries'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="contactEnquiries form content">
            <?= $this->Form->create($contactEnquiry) ?>
            <fieldset>
                <legend><?= __('Add Contact Enquiry') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('email');
                    echo $this->Form->control('subject');
                    echo $this->Form->control('message');
                    echo $this->Form->control('reply_message');
                    echo $this->Form->control('reply_status');
                    echo $this->Form->control('assigned_staff_id', ['options' => $assignedStaffs, 'empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
