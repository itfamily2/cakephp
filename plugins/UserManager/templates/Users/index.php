<?php $this->assign('title', 'Users'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-users text-primary me-2"></i>User Management</h4>
        <p class="text-muted mb-0">Manage system users, roles and access permissions.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-user-plus me-2"></i>Add User
    </a>
</div>

<!-- Filter Bar -->
<div class="glass-card p-3 mb-4">
    <form id="users-filter-form" method="get" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text" style="background:var(--bg-surface-2);border-color:var(--border-color);">
                    <i class="fa-solid fa-search text-muted" style="font-size:0.8rem;"></i>
                </span>
                <input type="text" name="search" id="users-search-input"
                       class="form-control"
                       placeholder="Search by username or email..."
                       value="<?= h($this->request->getQuery('search')) ?>"
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-2">
            <select name="status" id="users-status-select" class="form-select">
                <option value="">All Status</option>
                <option value="1" <?= $this->request->getQuery('status') === '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= $this->request->getQuery('status') === '0' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="fa-solid fa-filter me-1"></i>Filter
            </button>
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary" id="users-reset-btn">
                Reset
            </a>
        </div>
        <div class="col-auto">
            <span id="users-loading" class="text-muted small d-none">
                <i class="fa-solid fa-spinner fa-spin me-1"></i>Searching...
            </span>
        </div>
    </form>
</div>

<!-- Table Container — replaced by AJAX -->
<div class="glass-card" id="users-table-wrapper">
    <div id="users-table-container">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th><?= $this->Paginator->sort('last_login_time', 'Last Login') ?></th>
                        <th><?= $this->Paginator->sort('created', 'Joined') ?></th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users) || !count($users)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-5">
                        <i class="fa-solid fa-users-slash fa-2x d-block mb-2 opacity-50"></i>No users found
                    </td></tr>
                    <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                     style="width:36px;height:36px;background:var(--primary-light);color:var(--primary);font-size:0.85rem;">
                                    <?= strtoupper(substr($user->username, 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= h($user->username) ?></div>
                                    <div class="text-muted small"><?= h($user->email) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($user->user_roles)): ?>
                                <?php foreach ($user->user_roles as $ur): ?>
                                    <span class="badge bg-primary text-primary me-1" style="font-size:0.72rem;">
                                        <?= h($ur->role->name ?? '') ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small">No role</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $user->is_active ? 'bg-success text-success' : 'bg-secondary text-muted' ?>">
                                <?= $user->is_active ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <?php $verified = $user->email_verified ?? false; ?>
                            <i class="fa-solid fa-<?= $verified ? 'circle-check text-success' : 'circle-xmark text-danger' ?>"></i>
                        </td>
                        <td class="text-muted small"><?= !empty($user->last_login_time) ? $user->last_login_time->format('M d, Y H:i') : 'Never' ?></td>
                        <td class="text-muted small"><?= $user->created ? $user->created->format('M d, Y') : '-' ?></td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <?= $this->Html->link('<i class="fa-solid fa-eye"></i>', ['action' => 'view', $user->id], ['class' => 'btn btn-sm btn-outline-primary ajax-modal-link', 'escape' => false, 'title' => 'View User']) ?>
                                <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $user->id], ['class' => 'btn btn-sm btn-outline-secondary ajax-modal-link', 'escape' => false, 'title' => 'Edit User']) ?>
                                <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $user->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete user "' . h($user->username) . '"?', 'title' => 'Delete']) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border-color);">
            <p class="text-muted small mb-0"><?= $this->Paginator->counter('Showing {{current}} of {{count}} users') ?></p>
            <ul class="pagination pagination-sm mb-0">
                <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['escape' => false]) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['escape' => false]) ?>
            </ul>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var usersBaseUrl = '<?= $this->Url->build(['action' => 'index']) ?>';
    var searchTimer;

    function loadUsers(params) {
        $('#users-loading').removeClass('d-none');
        $.ajax({
            url: usersBaseUrl,
            data: params,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (html) {
                $('#users-table-container').html(html);
                // Update the browser URL without reload
                var queryStr = $.param(params, true);
                var newUrl = usersBaseUrl + (queryStr ? '?' + queryStr : '');
                window.history.pushState({}, '', newUrl);
            },
            error: function () {
                $('#users-table-container').html(
                    '<div class="text-center text-danger py-4"><i class="fa-solid fa-triangle-exclamation me-2"></i>Failed to load results. Please try again.</div>'
                );
            },
            complete: function () {
                $('#users-loading').addClass('d-none');
            }
        });
    }

    function getFilterParams() {
        return {
            search: $('#users-search-input').val().trim(),
            status: $('#users-status-select').val()
        };
    }

    // Submit on form submit (Filter button)
    $('#users-filter-form').on('submit', function (e) {
        e.preventDefault();
        loadUsers(getFilterParams());
    });

    // Live search while typing (debounced 400ms)
    $('#users-search-input').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            loadUsers(getFilterParams());
        }, 400);
    });

    // Filter immediately on status change
    $('#users-status-select').on('change', function () {
        loadUsers(getFilterParams());
    });

    // Pagination links inside the AJAX table — intercept with AJAX too
    $(document).on('click', '#users-table-container .pagination a', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var urlParams = new URLSearchParams(href.split('?')[1] || '');
        var params = getFilterParams();
        params.page = urlParams.get('page') || 1;
        loadUsers(params);
    });
});
</script>
