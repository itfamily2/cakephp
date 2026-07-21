<?php
$this->assign('title', 'Reports Summary');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 fw-bold"><i class="fa-solid fa-chart-bar text-primary me-2"></i>Reports Summary</h2>
        <p class="text-muted small mb-0">Aggregated ERP statistics generated at <?= h($data['generated_at']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="/reports/summary.json" class="btn btn-sm btn-outline-primary" target="_blank">
            <i class="fa-solid fa-code me-1"></i>JSON
        </a>
        <a href="/reports/export-csv" class="btn btn-sm btn-success">
            <i class="fa-solid fa-download me-1"></i>Export CSV
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Total Orders -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fa-solid fa-cart-shopping fa-lg text-white"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Orders</div>
                    <div class="h3 mb-0 fw-bold"><?= number_format($data['total_orders']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Total Users -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                    <i class="fa-solid fa-users fa-lg text-white"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Users</div>
                    <div class="h3 mb-0 fw-bold"><?= number_format($data['total_users']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Total Products -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <i class="fa-solid fa-box fa-lg text-white"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Products</div>
                    <div class="h3 mb-0 fw-bold"><?= number_format($data['total_products']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Total Invoices -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <i class="fa-solid fa-file-invoice fa-lg text-white"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Invoices</div>
                    <div class="h3 mb-0 fw-bold"><?= number_format($data['total_invoices']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3 border-bottom">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-info-circle me-2 text-primary"></i>Report Details</h6>
    </div>
    <div class="card-body p-4">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Metric</th>
                    <th class="text-end">Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $key => $val): ?>
                    <?php if ($key !== 'generated_at'): ?>
                    <tr>
                        <td class="text-capitalize"><?= h(str_replace('_', ' ', $key)) ?></td>
                        <td class="text-end fw-semibold"><?= number_format((int)$val) ?></td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="text-muted small mb-0 mt-2">
            <i class="fa-solid fa-clock me-1"></i>Generated at: <?= h($data['generated_at']) ?>
        </p>
    </div>
</div>
