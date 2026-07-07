<?php $this->assign('title', 'Reports & Analytics'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-chart-bar text-info me-2"></i>Reports & Analytics</h4>
        <p class="text-muted mb-0">Generate detailed business intelligence reports and export data.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'exportCsv']) ?>" class="btn btn-outline-success">
            <i class="fa-solid fa-file-csv me-2"></i>Export CSV
        </a>
    </div>
</div>

<!-- Quick Report Navigation -->
<div class="row g-4 mb-4">
    <?php
    $reportCards = [
        ['title'=>'Sales Report','desc'=>'Revenue trends and order analytics','icon'=>'chart-line','color'=>'success','action'=>'salesReport'],
        ['title'=>'User Report','desc'=>'Registration and activity patterns','icon'=>'users','color'=>'primary','action'=>'userReport'],
        ['title'=>'Inventory Report','desc'=>'Stock levels and product movement','icon'=>'boxes-stacked','color'=>'warning','action'=>'inventoryReport'],
        ['title'=>'Audit Report','desc'=>'System activity and security events','icon'=>'shield-halved','color'=>'danger','action'=>'auditReport'],
    ];
    foreach ($reportCards as $card):
    ?>
    <div class="col-md-3">
        <a href="<?= $this->Url->build(['action' => $card['action']]) ?>" class="text-decoration-none">
            <div class="glass-card p-4 h-100 text-center" style="transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
                <div class="rounded-3 p-3 d-inline-block mb-3" style="background:rgba(var(--bs-<?= $card['color'] ?>-rgb),0.15);">
                    <i class="fa-solid fa-<?= $card['icon'] ?> fa-lg text-<?= $card['color'] ?>"></i>
                </div>
                <h6 class="fw-bold mb-1"><?= $card['title'] ?></h6>
                <p class="text-muted small mb-0"><?= $card['desc'] ?></p>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- Date Range Filter -->
<div class="glass-card p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="fa-solid fa-calendar me-2"></i>Custom Date Range</h6>
    <form method="get" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label text-muted small">From Date</label>
            <input type="date" name="from" class="form-control border-secondary bg-transparent text-white"
                   value="<?= h($this->request->getQuery('from') ?? date('Y-m-01')) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted small">To Date</label>
            <input type="date" name="to" class="form-control border-secondary bg-transparent text-white"
                   value="<?= h($this->request->getQuery('to') ?? date('Y-m-d')) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small">Group By</label>
            <select name="group_by" class="form-select border-secondary bg-transparent text-white">
                <option value="day" <?= $this->request->getQuery('group_by') === 'day' ? 'selected' : '' ?>>Day</option>
                <option value="week" <?= $this->request->getQuery('group_by') === 'week' ? 'selected' : '' ?>>Week</option>
                <option value="month" <?= $this->request->getQuery('group_by') === 'month' ? 'selected' : '' ?>>Month</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill"><i class="fa-solid fa-magnifying-glass me-1"></i>Generate</button>
            <a href="<?= $this->Url->build(['action' => 'exportCsv', '?' => $this->request->getQueryParams()]) ?>" class="btn btn-outline-success">
                <i class="fa-solid fa-download me-1"></i>CSV
            </a>
        </div>
    </form>
</div>

<!-- Summary KPI Cards -->
<?php if (isset($summary)): ?>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="glass-card p-3 text-center">
            <h4 class="fw-bold text-success mb-1">₹<?= number_format($summary['revenue'] ?? 0, 2) ?></h4>
            <div class="text-muted small">Total Revenue</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card p-3 text-center">
            <h4 class="fw-bold text-primary mb-1"><?= number_format($summary['orders'] ?? 0) ?></h4>
            <div class="text-muted small">Orders Placed</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card p-3 text-center">
            <h4 class="fw-bold text-info mb-1"><?= number_format($summary['users'] ?? 0) ?></h4>
            <div class="text-muted small">New Users</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card p-3 text-center">
            <h4 class="fw-bold text-warning mb-1">₹<?= number_format(($summary['revenue'] ?? 0) / max($summary['orders'] ?? 1, 1), 2) ?></h4>
            <div class="text-muted small">Avg Order Value</div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Chart Placeholder -->
<div class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-chart-area text-primary me-2"></i>Revenue Trend</h6>
        <span class="text-muted small">Loaded via AJAX</span>
    </div>
    <div id="chartContainer" class="d-flex align-items-center justify-content-center" style="height:300px;">
        <div class="text-center text-muted">
            <i class="fa-solid fa-chart-line fa-3x d-block mb-3 opacity-30"></i>
            <p class="mb-0">Chart loads after applying date filter</p>
        </div>
    </div>
</div>
