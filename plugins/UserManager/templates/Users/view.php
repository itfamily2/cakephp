<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __d('UserManager', 'Actions') ?></h4>
            <?= $this->Html->link(__d('UserManager', 'Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__d('UserManager', 'Delete User'), ['action' => 'delete', $user->id], ['confirm' => __d('UserManager', 'Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__d('UserManager', 'List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__d('UserManager', 'New User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users view content">
            <h3><?= h($user->username) ?></h3>
            <table>
                <tr>
                    <th><?= __d('UserManager', 'Username') ?></th>
                    <td><?= h($user->username) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Profile Image') ?></th>
                    <td><?= h($user->profile_image) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Last Login Ip') ?></th>
                    <td><?= h($user->last_login_ip) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Remember Token') ?></th>
                    <td><?= h($user->remember_token) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Verification Token') ?></th>
                    <td><?= h($user->verification_token) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Password Reset Token') ?></th>
                    <td><?= h($user->password_reset_token) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Last Login Time') ?></th>
                    <td><?= h($user->last_login_time) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Password Reset Expiry') ?></th>
                    <td><?= h($user->password_reset_expiry) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Created') ?></th>
                    <td><?= h($user->created) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Modified') ?></th>
                    <td><?= h($user->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Is Active') ?></th>
                    <td><?= $user->is_active ? __d('UserManager', 'Yes') : __d('UserManager', 'No'); ?></td>
                </tr>
                <tr>
                    <th><?= __d('UserManager', 'Email Verified') ?></th>
                    <td><?= $user->email_verified ? __d('UserManager', 'Yes') : __d('UserManager', 'No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>