<?php
$this->assign('title', 'Users Management');
?>

<div class="row g-4 mb-4">
    <!-- Top action bar -->
    <div class="col-12">
        <div class="card glass-card p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h4 class="fw-bold text-white m-0"><i class="fa-solid fa-users text-primary me-2"></i>User Accounts</h4>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Add Single User
                    </a>
                    
                    <a href="<?= $this->Url->build(['action' => 'exportCsv']) ?>" class="btn btn-outline-light btn-sm">
                        <i class="fa-solid fa-file-export me-1"></i> Export CSV
                    </a>

                    <!-- CSV Import Form -->
                    <?= $this->Form->create(null, [
                        'url' => ['action' => 'addMultipleCsv'],
                        'type' => 'file',
                        'class' => 'd-flex align-items-center gap-2 border-start border-secondary ps-3 ms-2'
                    ]) ?>
                        <input type="file" name="csv_file" class="form-control form-control-sm" accept=".csv" required style="max-width: 200px;">
                        <button type="submit" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-file-import me-1"></i> Import CSV
                        </button>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Filter bar -->
    <div class="col-12">
        <div class="card glass-card p-3">
            <div class="row g-2 align-items-center">
                <!-- Search input with autocomplete -->
                <div class="col-md-6 position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by username or email..." value="<?= h($search) ?>" autocomplete="off">
                    </div>
                    <div id="autocompleteBox" class="autocomplete-suggestions w-100" style="display:none; top: 100%;"></div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Active Only</option>
                        <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Inactive Only</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="col-md-3">
                    <button id="btnResetFilters" class="btn btn-outline-secondary w-100"><i class="fa-solid fa-filter-circle-xmark me-1"></i> Reset Filters</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Users list container -->
    <div class="col-12">
        <div class="card glass-card p-4">
            <div id="usersTableContainer">
                <?= $this->element('../Users/ajax_index') ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let debounceTimer;

    function fetchUsers() {
        const searchVal = $('#searchInput').val();
        const statusVal = $('#statusFilter').val();
        const url = '<?= $this->Url->build(['action' => 'index']) ?>';

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                search: searchVal,
                status: statusVal
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(html) {
                $('#usersTableContainer').html(html);
            },
            error: function() {
                console.error("AJAX search failed.");
            }
        });
    }

    // Debounce keyup search
    $('#searchInput').on('keyup', function() {
        clearTimeout(debounceTimer);
        const searchVal = $(this).val();

        // Autocomplete suggestions check
        if (searchVal.length >= 2) {
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'index']) ?>.json',
                type: 'GET',
                data: {
                    search: searchVal,
                    suggest: 1
                },
                success: function(res) {
                    let html = '';
                    if (res && res.length > 0) {
                        res.forEach(item => {
                            html += `<div class="autocomplete-suggestion" data-val="${item.username}">${item.username} (${item.email})</div>`;
                        });
                        $('#autocompleteBox').html(html).show();
                    } else {
                        $('#autocompleteBox').hide();
                    }
                }
            });
        } else {
            $('#autocompleteBox').hide();
        }

        debounceTimer = setTimeout(fetchUsers, 300);
    });

    // Handle autocomplete selection
    $(document).on('click', '.autocomplete-suggestion', function() {
        const val = $(this).attr('data-val');
        $('#searchInput').val(val);
        $('#autocompleteBox').hide();
        fetchUsers();
    });

    // Close autocomplete when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#searchInput, #autocompleteBox').length) {
            $('#autocompleteBox').hide();
        }
    });

    // Handle status change
    $('#statusFilter').on('change', fetchUsers);

    // Reset filters
    $('#btnResetFilters').on('click', function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#autocompleteBox').hide();
        fetchUsers();
    });

    // Intercept AJAX pagination and sorting
    $(document).on('click', '#usersTableContainer th a, #usersTableContainer .pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (!url) return;

        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(html) {
                $('#usersTableContainer').html(html);
            },
            error: function() {
                console.error("AJAX navigation failed.");
            }
        });
    });
});
</script>
