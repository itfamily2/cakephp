<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AuditLog $auditLog
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Audit Log'), ['action' => 'edit', $auditLog->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Audit Log'), ['action' => 'delete', $auditLog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $auditLog->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Audit Logs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Audit Log'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="auditLogs view content">
            <h3><?= h($auditLog->table_name) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $auditLog->hasValue('user') ? $this->Html->link($auditLog->user->username, ['controller' => 'Users', 'action' => 'view', $auditLog->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Table Name') ?></th>
                    <td><?= h($auditLog->table_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Action') ?></th>
                    <td><?= h($auditLog->action) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ip Address') ?></th>
                    <td><?= h($auditLog->ip_address) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($auditLog->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Row Id') ?></th>
                    <td><?= $this->Number->format($auditLog->row_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($auditLog->created) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Old Values') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($auditLog->old_values)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('New Values') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($auditLog->new_values)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>