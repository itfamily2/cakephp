<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Permission> $permissions
 */
?>
<div class="permissions index content">
    <?= $this->Html->link(__('New Permission'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Permissions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('role_id') ?></th>
                    <th><?= $this->Paginator->sort('group_id') ?></th>
                    <th><?= $this->Paginator->sort('controller') ?></th>
                    <th><?= $this->Paginator->sort('action') ?></th>
                    <th><?= $this->Paginator->sort('allowed') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($permissions as $permission): ?>
                <tr>
                    <td><?= $this->Number->format($permission->id) ?></td>
                    <td><?= $permission->hasValue('role') ? $this->Html->link($permission->role->name, ['controller' => 'Roles', 'action' => 'view', $permission->role->id]) : '' ?></td>
                    <td><?= $permission->hasValue('group') ? $this->Html->link($permission->group->name, ['controller' => 'Groups', 'action' => 'view', $permission->group->id]) : '' ?></td>
                    <td><?= h($permission->controller) ?></td>
                    <td><?= h($permission->action) ?></td>
                    <td><?= h($permission->allowed) ?></td>
                    <td><?= h($permission->created) ?></td>
                    <td><?= h($permission->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $permission->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $permission->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $permission->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $permission->id),
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