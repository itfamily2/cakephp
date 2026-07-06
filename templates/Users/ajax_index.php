<div class="table-responsive">
    <table class="table custom-table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th><?= $this->Paginator->sort('username', 'Username') ?></th>
                <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                <th>Role</th>
                <th>Group</th>
                <th><?= $this->Paginator->sort('is_active', 'Active') ?></th>
                <th><?= $this->Paginator->sort('email_verified', 'Verified') ?></th>
                <th><?= $this->Paginator->sort('last_login_time', 'Last Login') ?></th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) === 0): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">No users found matching query.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= h($user->id) ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if (!empty($user->profile_image)): ?>
                                    <img src="<?= $this->Url->build('/uploads/profiles/' . $user->profile_image) ?>" class="rounded-circle me-2" width="28" height="28" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-2" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                        <?= strtoupper(substr($user->username, 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="fw-bold text-white"><?= h($user->username) ?></span>
                            </div>
                        </td>
                        <td><?= h($user->email) ?></td>
                        <td>
                            <span class="badge bg-primary bg-opacity-20 text-primary">
                                <?= !empty($user->user_roles) ? h($user->user_roles[0]->role->name) : 'No Role' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-20 text-secondary">
                                <?= !empty($user->group_users) ? h($user->group_users[0]->group->name) : 'No Group' ?>
                            </span>
                        </td>
                        <td>
                            <!-- Activate/Inactivate toggle form -->
                            <?= $this->Form->postLink(
                                $user->is_active ? '<span class="badge-active"><i class="fa-solid fa-check me-1"></i> Active</span>' : '<span class="badge-inactive"><i class="fa-solid fa-xmark me-1"></i> Inactive</span>',
                                ['action' => 'toggleActive', $user->id],
                                [
                                    'escapeTitle' => false,
                                    'confirm' => __('Are you sure you want to change status for {0}?', $user->username)
                                ]
                            ) ?>
                        </td>
                        <td>
                            <?php if ($user->email_verified): ?>
                                <span class="text-success" title="Email Verified"><i class="fa-solid fa-circle-check fs-5"></i></span>
                            <?php else: ?>
                                <span class="text-warning" title="Pending Verification"><i class="fa-solid fa-circle-exclamation fs-5"></i></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $user->last_login_time ? h($user->last_login_time->format('Y-m-d H:i')) : '<span class="text-muted small">Never</span>' ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= $this->Url->build(['action' => 'edit', $user->id]) ?>" class="btn btn-outline-primary btn-sm me-1" title="Edit"><i class="fa-solid fa-pencil"></i></a>
                            
                            <?= $this->Form->postLink(
                                '<i class="fa-solid fa-trash"></i>',
                                ['action' => 'delete', $user->id],
                                [
                                    'escapeTitle' => false,
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'title' => 'Delete',
                                    'confirm' => __('Are you sure you want to delete user {0}?', $user->username)
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
