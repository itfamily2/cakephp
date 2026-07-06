<div class="table-responsive">
    <table class="table custom-table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th>User</th>
                <th><?= $this->Paginator->sort('action', 'Action') ?></th>
                <th><?= $this->Paginator->sort('description', 'Description') ?></th>
                <th><?= $this->Paginator->sort('ip_address', 'IP Address') ?></th>
                <th><?= $this->Paginator->sort('created', 'Time') ?></th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($activityLogs) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No activity logs found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($activityLogs as $log): ?>
                    <tr>
                        <td><?= h($log->id) ?></td>
                        <td><?= $log->hasValue('user') ? h($log->user->username) : '<span class="text-muted small">Guest/System</span>' ?></td>
                        <td><span class="badge bg-secondary"><?= h($log->action) ?></span></td>
                        <td><?= h($log->description) ?></td>
                        <td><?= h($log->ip_address ?: 'N/A') ?></td>
                        <td><?= h($log->created->format('Y-m-d H:i:s')) ?></td>
                        <td class="text-end">
                            <a href="<?= $this->Url->build(['action' => 'view', $log->id]) ?>" class="btn btn-outline-info btn-sm" title="View"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2">
    <div class="text-muted small">
        <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total')) ?>
    </div>
    <ul class="pagination pagination-sm m-0">
        <?= $this->Paginator->first('<< First') ?>
        <?= $this->Paginator->prev('< Prev') ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('Next >') ?>
        <?= $this->Paginator->last('Last >>') ?>
    </ul>
</div>
