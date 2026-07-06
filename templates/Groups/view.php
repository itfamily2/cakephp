<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group $group
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Group'), ['action' => 'edit', $group->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Group'), ['action' => 'delete', $group->id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Groups'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Group'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="groups view content">
            <h3><?= h($group->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Parent Group') ?></th>
                    <td><?= $group->hasValue('parent_group') ? $this->Html->link($group->parent_group->name, ['controller' => 'Groups', 'action' => 'view', $group->parent_group->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($group->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($group->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($group->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($group->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Registration Allowed') ?></th>
                    <td><?= $group->registration_allowed ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Group Users') ?></h4>
                <?php if (!empty($group->group_users)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($group->group_users as $groupUser) : ?>
                        <tr>
                            <td><?= h($groupUser->id) ?></td>
                            <td><?= h($groupUser->user_id) ?></td>
                            <td><?= h($groupUser->created) ?></td>
                            <td><?= h($groupUser->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'GroupUsers', 'action' => 'view', $groupUser->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'GroupUsers', 'action' => 'edit', $groupUser->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'GroupUsers', 'action' => 'delete', $groupUser->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $groupUser->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Groups') ?></h4>
                <?php if (!empty($group->child_groups)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Registration Allowed') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($group->child_groups as $childGroup) : ?>
                        <tr>
                            <td><?= h($childGroup->id) ?></td>
                            <td><?= h($childGroup->name) ?></td>
                            <td><?= h($childGroup->registration_allowed) ?></td>
                            <td><?= h($childGroup->created) ?></td>
                            <td><?= h($childGroup->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Groups', 'action' => 'view', $childGroup->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Groups', 'action' => 'edit', $childGroup->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Groups', 'action' => 'delete', $childGroup->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $childGroup->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Permissions') ?></h4>
                <?php if (!empty($group->permissions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Role Id') ?></th>
                            <th><?= __('Controller') ?></th>
                            <th><?= __('Action') ?></th>
                            <th><?= __('Allowed') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($group->permissions as $permission) : ?>
                        <tr>
                            <td><?= h($permission->id) ?></td>
                            <td><?= h($permission->role_id) ?></td>
                            <td><?= h($permission->controller) ?></td>
                            <td><?= h($permission->action) ?></td>
                            <td><?= h($permission->allowed) ?></td>
                            <td><?= h($permission->created) ?></td>
                            <td><?= h($permission->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Permissions', 'action' => 'view', $permission->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Permissions', 'action' => 'edit', $permission->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Permissions', 'action' => 'delete', $permission->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $permission->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>