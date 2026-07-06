<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\EmailSignature> $emailSignatures
 */
?>
<div class="emailSignatures index content">
    <?= $this->Html->link(__('New Email Signature'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Email Signatures') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emailSignatures as $emailSignature): ?>
                <tr>
                    <td><?= $this->Number->format($emailSignature->id) ?></td>
                    <td><?= h($emailSignature->name) ?></td>
                    <td><?= $emailSignature->hasValue('user') ? $this->Html->link($emailSignature->user->username, ['controller' => 'Users', 'action' => 'view', $emailSignature->user->id]) : '' ?></td>
                    <td><?= h($emailSignature->created) ?></td>
                    <td><?= h($emailSignature->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $emailSignature->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $emailSignature->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $emailSignature->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $emailSignature->id),
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