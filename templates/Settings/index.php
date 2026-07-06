<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Setting> $settings
 */
?>
<div class="settings index content">
    <?= $this->Html->link(__('New Setting'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Settings') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('key') ?></th>
                    <th><?= $this->Paginator->sort('input_type') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settings as $setting): ?>
                <tr>
                    <td><?= $this->Number->format($setting->id) ?></td>
                    <td><?= h($setting->key) ?></td>
                    <td><?= h($setting->input_type) ?></td>
                    <td><?= h($setting->created) ?></td>
                    <td><?= h($setting->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $setting->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $setting->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $setting->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $setting->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>