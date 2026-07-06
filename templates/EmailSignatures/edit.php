<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EmailSignature $emailSignature
 * @var string[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $emailSignature->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $emailSignature->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Email Signatures'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="emailSignatures form content">
            <?= $this->Form->create($emailSignature) ?>
            <fieldset>
                <legend><?= __('Edit Email Signature') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('body');
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
