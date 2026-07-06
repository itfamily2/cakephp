<?php
$this->assign('title', 'Roles Management');
?>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card glass-card p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h4 class="fw-bold text-white m-0"><i class="fa-solid fa-user-shield text-primary me-2"></i>User Roles</h4>
                <div>
                    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Add Role
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card glass-card p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-9">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search roles by name..." value="<?= h($search ?? '') ?>" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <button id="btnResetFilters" class="btn btn-outline-secondary w-100"><i class="fa-solid fa-filter-circle-xmark me-1"></i> Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card glass-card p-4">
            <div id="rolesTableContainer">
                <?= $this->element('../Roles/ajax_index') ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let debounceTimer;

    function fetchRoles() {
        const searchVal = $('#searchInput').val();
        const url = '<?= $this->Url->build(['action' => 'index']) ?>';

        $.ajax({
            url: url,
            type: 'GET',
            data: { search: searchVal },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(html) {
                $('#rolesTableContainer').html(html);
            },
            error: function() {
                console.error("AJAX search failed.");
            }
        });
    }

    $('#searchInput').on('keyup', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchRoles, 300);
    });

    $('#btnResetFilters').on('click', function() {
        $('#searchInput').val('');
        fetchRoles();
    });

    $(document).on('click', '#rolesTableContainer th a, #rolesTableContainer .pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (!url) return;

        $.ajax({
            url: url,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(html) {
                $('#rolesTableContainer').html(html);
            },
            error: function() {
                console.error("AJAX navigation failed.");
            }
        });
    });
});
</script>