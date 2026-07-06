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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $cmsPage->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $cmsPage->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Cms Pages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="cmsPages form content">
            <?= $this->Form->create($cmsPage) ?>
            <fieldset>
                <legend><?= __('Edit Cms Page') ?></legend>
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
