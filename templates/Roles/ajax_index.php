<div class="table-responsive">
    <table class="table custom-table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th><?= $this->Paginator->sort('name', 'Name') ?></th>
                <th><?= $this->Paginator->sort('created', 'Created') ?></th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($roles) === 0): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No roles found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= h($role->id) ?></td>
                        <td><span class="fw-bold text-white"><?= h($role->name) ?></span></td>
                        <td><?= h($role->created->format('Y-m-d H:i')) ?></td>
                        <td class="text-end">
                            <a href="<?= $this->Url->build(['action' => 'view', $role->id]) ?>" class="btn btn-outline-info btn-sm me-1" title="View"><i class="fa-solid fa-eye"></i></a>
                            <a href="<?= $this->Url->build(['action' => 'edit', $role->id]) ?>" class="btn btn-outline-primary btn-sm me-1" title="Edit"><i class="fa-solid fa-pencil"></i></a>
                            <?= $this->Form->postLink(
                                '<i class="fa-solid fa-trash"></i>',
                                ['action' => 'delete', $role->id],
                                [
                                    'escapeTitle' => false,
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'title' => 'Delete',
                                    'confirm' => __('Are you sure you want to delete role {0}?', $role->name)
                                ]
                            ) ?>
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
