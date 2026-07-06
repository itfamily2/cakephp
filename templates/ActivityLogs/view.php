<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ActivityLog $activityLog
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Activity Log'), ['action' => 'edit', $activityLog->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Activity Log'), ['action' => 'delete', $activityLog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $activityLog->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Activity Logs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Activity Log'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="activityLogs view content">
            <h3><?= h($activityLog->action) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $activityLog->hasValue('user') ? $this->Html->link($activityLog->user->username, ['controller' => 'Users', 'action' => 'view', $activityLog->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Action') ?></th>
                    <td><?= h($activityLog->action) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ip Address') ?></th>
                    <td><?= h($activityLog->ip_address) ?></td>
                </tr>
                <tr>
                    <th><?= __('User Agent') ?></th>
                    <td><?= h($activityLog->user_agent) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($activityLog->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($activityLog->created) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($activityLog->description)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>