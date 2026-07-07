<div class="table-responsive">
    <table class="table custom-table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th><?= $this->Paginator->sort('name', 'Name') ?></th>
                <th>Parent Group</th>
                <th><?= $this->Paginator->sort('registration_allowed', 'Reg. Allowed') ?></th>
                <th><?= $this->Paginator->sort('created', 'Created') ?></th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($groups) === 0): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No groups found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($groups as $group): ?>
                    <tr>
                        <td><?= h($group->id) ?></td>
                        <td><span class="fw-bold text-white"><?= h($group->name) ?></span></td>
                        <td>
                            <?= $group->hasValue('parent_group') ? h($group->parent_group->name) : '<span class="text-muted small">None</span>' ?>
                        </td>
                        <td>
                            <?php if ($group->registration_allowed): ?>
                                <span class="badge bg-success bg-opacity-20 text-success">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-20 text-secondary">No</span>
                            <?php endif; ?>
                        </td>
                        <td><?= h($group->created->format('Y-m-d H:i')) ?></td>
                        <td class="text-end">
                            <a href="<?= $this->Url->build(['action' => 'view', $group->id]) ?>" class="btn btn-outline-info btn-sm me-1" title="View"><i class="fa-solid fa-eye"></i></a>
                            <a href="<?= $this->Url->build(['action' => 'edit', $group->id]) ?>" class="btn btn-outline-primary btn-sm me-1" title="Edit"><i class="fa-solid fa-pencil"></i></a>
                            <?= $this->Form->postLink(
                                '<i class="fa-solid fa-trash"></i>',
                                ['action' => 'delete', $group->id],
                                [
                                    'escapeTitle' => false,
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'title' => 'Delete',
                                    'confirm' => __('Are you sure you want to delete group {0}?', $group->name)
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
