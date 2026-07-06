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
            <?= $this->Html->link(__('Edit Cms Page'), ['action' => 'edit', $cmsPage->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Cms Page'), ['action' => 'delete', $cmsPage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cmsPage->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Cms Pages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Cms Page'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="cmsPages view content">
            <h3><?= h($cmsPage->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($cmsPage->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Slug') ?></th>
                    <td><?= h($cmsPage->slug) ?></td>
                </tr>
                <tr>
                    <th><?= __('Meta Title') ?></th>
                    <td><?= h($cmsPage->meta_title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($cmsPage->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($cmsPage->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($cmsPage->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Content') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($cmsPage->content)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Meta Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($cmsPage->meta_description)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>