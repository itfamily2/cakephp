<div class="dashboard-header">
    <h1>Enterprise Overview</h1>
    <p>Welcome back! Here's what's happening today.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card glass-panel stat-users">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
        </div>
        <div class="stat-content">
            <h3>Total Users</h3>
            <p class="stat-value"><?= number_format($usersCount) ?></p>
        </div>
    </div>

    <div class="stat-card glass-panel stat-orders">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        </div>
        <div class="stat-content">
            <h3>Total Orders</h3>
            <p class="stat-value"><?= number_format($ordersCount) ?></p>
        </div>
    </div>

    <div class="stat-card glass-panel stat-revenue">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div class="stat-content">
            <h3>Revenue</h3>
            <p class="stat-value">₹<?= number_format((float)$totalRevenue, 2) ?></p>
        </div>
    </div>
</div>

<div class="dashboard-body">
    <!-- Audit Log Section Powered By Audit Plugin -->
    <div class="glass-panel audit-log-panel">
        <div class="panel-header">
            <h2>Recent Security Audits</h2>
            <button class="btn btn-sm btn-primary">View All Logs</button>
        </div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Model (Table)</th>
                        <th>Record ID</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($auditLogs)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No recent audits found.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($auditLogs as $log): ?>
                        <tr>
                            <td>
                                <span class="badge badge-<?= strtolower($log->action) ?>">
                                    <?= h($log->action) ?>
                                </span>
                            </td>
                            <td><?= h($log->model) ?></td>
                            <td>#<?= h($log->foreign_key) ?></td>
                            <td class="text-muted"><?= $log->created->format('Y-m-d H:i') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Settings Section -->
    <div class="glass-panel quick-settings">
        <div class="panel-header">
            <h2>System Status</h2>
        </div>
        <div class="status-list">
            <div class="status-item">
                <span class="status-label">Plugins Loaded</span>
                <span class="status-value text-success">8 / 8 Active</span>
            </div>
            <div class="status-item">
                <span class="status-label">API Gateway</span>
                <span class="status-value text-success">Online (v1)</span>
            </div>
            <div class="status-item">
                <span class="status-label">Background Queue</span>
                <span class="status-value text-warning">Idle</span>
            </div>
            <div class="status-item mt-4">
                <button class="btn btn-outline btn-full">Run Migration Sync</button>
            </div>
        </div>
    </div>
</div>
