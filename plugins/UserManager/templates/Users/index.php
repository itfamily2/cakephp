<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\Cake\Datasource\EntityInterface> $users
 */
?>
<div class="users index content">
    <?= $this->Html->link(__d('UserManager', 'New User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __d('UserManager', 'Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('username') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('profile_image') ?></th>
                    <th><?= $this->Paginator->sort('last_login_time') ?></th>
                    <th><?= $this->Paginator->sort('last_login_ip') ?></th>
                    <th><?= $this->Paginator->sort('remember_token') ?></th>
                    <th><?= $this->Paginator->sort('is_active') ?></th>
                    <th><?= $this->Paginator->sort('email_verified') ?></th>
                    <th><?= $this->Paginator->sort('verification_token') ?></th>
                    <th><?= $this->Paginator->sort('password_reset_token') ?></th>
                    <th><?= $this->Paginator->sort('password_reset_expiry') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __d('UserManager', 'Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $this->Number->format($user->id) ?></td>
                    <td><?= h($user->username) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td><?= h($user->profile_image) ?></td>
                    <td><?= h($user->last_login_time) ?></td>
                    <td><?= h($user->last_login_ip) ?></td>
                    <td><?= h($user->remember_token) ?></td>
                    <td><?= h($user->is_active) ?></td>
                    <td><?= h($user->email_verified) ?></td>
                    <td><?= h($user->verification_token) ?></td>
                    <td><?= h($user->password_reset_token) ?></td>
                    <td><?= h($user->password_reset_expiry) ?></td>
                    <td><?= h($user->created) ?></td>
                    <td><?= h($user->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__d('UserManager', 'View'), ['action' => 'view', $user->id]) ?>
                        <?= $this->Html->link(__d('UserManager', 'Edit'), ['action' => 'edit', $user->id]) ?>
                        <?= $this->Form->postLink(
                            __d('UserManager', 'Delete'),
                            ['action' => 'delete', $user->id],
                            [
                                'method' => 'delete',
                                'confirm' => __d('UserManager', 'Are you sure you want to delete # {0}?', $user->id),
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
            <?= $this->Paginator->first('<< ' . __d('UserManager', 'first')) ?>
            <?= $this->Paginator->prev('< ' . __d('UserManager', 'previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__d('UserManager', 'next') . ' >') ?>
            <?= $this->Paginator->last(__d('UserManager', 'last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__d('UserManager', 'Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>