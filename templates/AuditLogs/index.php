<?php
$this->assign('title', 'Audit Logs');
?>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card glass-card p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h4 class="fw-bold text-white m-0"><i class="fa-solid fa-shield-halved text-primary me-2"></i>Audit Logs</h4>
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
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by username, module, action, IP address..." value="<?= h($search ?? '') ?>" autocomplete="off">
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
            <div id="auditLogsTableContainer">
                <?= $this->element('../AuditLogs/ajax_index') ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let debounceTimer;

    function fetchLogs() {
        const searchVal = $('#searchInput').val();
        const url = '<?= $this->Url->build(['action' => 'index']) ?>';

        $.ajax({
            url: url,
            type: 'GET',
            data: { search: searchVal },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(html) {
                $('#auditLogsTableContainer').html(html);
            },
            error: function() {
                console.error("AJAX search failed.");
            }
        });
    }

    $('#searchInput').on('keyup', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchLogs, 300);
    });

    $('#btnResetFilters').on('click', function() {
        $('#searchInput').val('');
        fetchLogs();
    });

    $(document).on('click', '#auditLogsTableContainer th a, #auditLogsTableContainer .pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (!url) return;

        $.ajax({
            url: url,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(html) {
                $('#auditLogsTableContainer').html(html);
            },
            error: function() {
                console.error("AJAX navigation failed.");
            }
        });
    });
});
</script>