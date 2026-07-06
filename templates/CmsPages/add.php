<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CmsPage $cmsPage
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Cms Pages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="cmsPages form content">
            <?= $this->Form->create($cmsPage) ?>
            <fieldset>
                <legend><?= __('Add Cms Page') ?></legend>
                <?php
                    echo $this->Form->control('title');
                    echo $this->Form->control('slug');
                    echo $this->Form->control('content');
                    echo $this->Form->control('meta_title');
                    echo $this->Form->control('meta_description');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
