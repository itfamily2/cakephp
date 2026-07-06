<div class="table-responsive">
    <table class="table custom-table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th><?= $this->Paginator->sort('name', 'Name') ?></th>
                <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                <th><?= $this->Paginator->sort('subject', 'Subject') ?></th>
                <th><?= $this->Paginator->sort('reply_status', 'Status') ?></th>
                <th>Assigned Staff</th>
                <th><?= $this->Paginator->sort('created', 'Date') ?></th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($contactEnquiries) === 0): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No enquiries found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($contactEnquiries as $enquiry): ?>
                    <tr>
                        <td><?= h($enquiry->id) ?></td>
                        <td><span class="fw-bold text-white"><?= h($enquiry->name) ?></span></td>
                        <td><?= h($enquiry->email) ?></td>
                        <td><?= h($enquiry->subject) ?></td>
                        <td>
                            <?php if ($enquiry->reply_status === 'replied'): ?>
                                <span class="badge bg-success bg-opacity-20 text-success">Replied</span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-20 text-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $enquiry->hasValue('assigned_staff') ? h($enquiry->assigned_staff->username) : '<span class="text-muted small">Unassigned</span>' ?>
                        </td>
                        <td><?= h($enquiry->created->format('Y-m-d H:i')) ?></td>
                        <td class="text-end">
                            <a href="<?= $this->Url->build(['action' => 'view', $enquiry->id]) ?>" class="btn btn-outline-info btn-sm me-1" title="View"><i class="fa-solid fa-eye"></i></a>
                            <a href="<?= $this->Url->build(['action' => 'edit', $enquiry->id]) ?>" class="btn btn-outline-primary btn-sm me-1" title="Edit"><i class="fa-solid fa-reply"></i></a>
                            <?= $this->Form->postLink(
                                '<i class="fa-solid fa-trash"></i>',
                                ['action' => 'delete', $enquiry->id],
                                [
                                    'escapeTitle' => false,
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'title' => 'Delete',
                                    'confirm' => __('Are you sure you want to delete enquiry from {0}?', $enquiry->name)
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
