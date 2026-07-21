<?php $this->assign('title', 'Dashboard'); ?>

<style>
/* Modern Next-Gen UI Styles */
.dashboard-container { font-family: 'Inter', sans-serif; }
.card-sleek {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    transition: all 0.2s ease;
}
.card-sleek:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
}
.kpi-icon-box {
    width: 36px; height: 36px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
}
.table-sleek { font-size: 0.85rem; margin-bottom: 0; }
.table-sleek th {
    background: #f8fafc;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    padding: 6px 12px;
}
.table-sleek td {
    padding: 6px 12px;
    font-size: 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}
.table-sleek tr:last-child td { border-bottom: none; }
.table-sleek tr:hover td { background-color: #f8fafc; }
.badge-sleek {
    padding: 4px 8px;
    font-size: 0.7rem;
    font-weight: 600;
    border-radius: 4px;
}
.dashboard-header { font-size: 1.2rem; font-weight: 700; color: #0f172a; }
.text-micro { font-size: 0.75rem; color: #64748b; }
.card-title-sleek { font-size: 0.85rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
</style>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h5 class="dashboard-header mb-0">Overview</h5>
            <p class="text-micro mb-0">Welcome, <strong><?= h($currentUser->username ?? 'Admin') ?></strong> &middot; <?= date('M j, Y') ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'clearCache']) ?>" class="btn btn-sm btn-light border shadow-sm py-1" style="font-size: 0.75rem;">
                <i class="fa-solid fa-rotate text-muted me-1"></i> Sync
            </a>
        </div>
    </div>

    <!-- KPI Stats Row -->
    <div class="row g-2 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card-sleek p-2 px-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-micro fw-bold text-uppercase" style="font-size: 0.65rem;">Total Users</span>
                    <div class="kpi-icon-box" style="width:28px;height:28px;background: #eef2ff;"><i class="fa-solid fa-users fa-xs" style="color: #4f46e5;"></i></div>
                </div>
                <div class="d-flex align-items-end justify-content-between">
                    <h4 class="fw-bold mb-0" style="color: #0f172a; line-height: 1;"><?= number_format($totalUsers ?? 0) ?></h4>
                    <span class="badge badge-sleek bg-success bg-opacity-10 text-success"><i class="fa-solid fa-arrow-up me-1"></i>12%</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card-sleek p-2 px-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-micro fw-bold text-uppercase" style="font-size: 0.65rem;">Live Orders</span>
                    <div class="kpi-icon-box" style="width:28px;height:28px;background: #ecfdf5;"><i class="fa-solid fa-cart-shopping fa-xs" style="color: #10b981;"></i></div>
                </div>
                <div class="d-flex align-items-end justify-content-between">
                    <h4 class="fw-bold mb-0" style="color: #0f172a; line-height: 1;"><?= number_format($totalOrders ?? 0) ?></h4>
                    <span class="badge badge-sleek bg-success bg-opacity-10 text-success"><i class="fa-solid fa-arrow-up me-1"></i>8%</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card-sleek p-2 px-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-micro fw-bold text-uppercase" style="font-size: 0.65rem;">Catalog Items</span>
                    <div class="kpi-icon-box" style="width:28px;height:28px;background: #fffbeb;"><i class="fa-solid fa-box fa-xs" style="color: #d97706;"></i></div>
                </div>
                <div class="d-flex align-items-end justify-content-between">
                    <h4 class="fw-bold mb-0" style="color: #0f172a; line-height: 1;"><?= number_format($totalProducts ?? 0) ?></h4>
                    <span class="text-micro text-muted">Active SKUs</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card-sleek p-2 px-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-micro fw-bold text-uppercase" style="font-size: 0.65rem;">Pending Support</span>
                    <div class="kpi-icon-box" style="width:28px;height:28px;background: #fef2f2;"><i class="fa-solid fa-envelope-open-text fa-xs" style="color: #ef4444;"></i></div>
                </div>
                <div class="d-flex align-items-end justify-content-between">
                    <h4 class="fw-bold mb-0" style="color: #0f172a; line-height: 1;"><?= number_format($pendingEnquiries ?? 0) ?></h4>
                    <span class="badge badge-sleek bg-danger bg-opacity-10 text-danger">Action</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="row g-3 mb-3">
        <!-- Orders & Charts -->
        <div class="col-xl-8 d-flex flex-column gap-3">
            
            <!-- Charts Row (Compressed) -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card-sleek p-3">
                        <h6 class="card-title-sleek mb-2">Revenue (7 Days)</h6>
                        <div style="height: 140px;"><canvas id="revenueChart"></canvas></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-sleek p-3">
                        <h6 class="card-title-sleek mb-2">Traffic (7 Days)</h6>
                        <div style="height: 140px;"><canvas id="trafficChart"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Data Grid -->
            <div class="card-sleek flex-grow-1">
                <div class="d-flex justify-content-between align-items-center p-2 px-3 border-bottom border-light">
                    <h6 class="card-title-sleek mb-0" style="font-size: 0.75rem;">Recent Transactions</h6>
                    <a href="<?= $this->Url->build(['controller' => 'Orders', 'action' => 'index']) ?>" class="text-micro text-decoration-none">View All &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sleek table-hover table-borderless">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentOrders)): ?>
                                <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td class="fw-bold" style="color: #0f172a;">#<?= h($order->order_number ?? $order->id) ?></td>
                                    <td><?= h($order->user->username ?? 'Guest') ?></td>
                                    <td class="fw-semibold">₹<?= number_format($order->total ?? 0, 2) ?></td>
                                    <td>
                                        <?php
                                        $statusColors = ['Pending'=>'warning','Processing'=>'info','Shipped'=>'primary','Delivered'=>'success','Cancelled'=>'danger'];
                                        $sc = $statusColors[$order->status ?? 'Pending'] ?? 'secondary';
                                        ?>
                                        <span class="badge badge-sleek bg-<?= $sc ?> bg-opacity-10 text-<?= $sc ?>"><?= h($order->status ?? 'Pending') ?></span>
                                    </td>
                                    <td class="text-muted"><?= $order->created ? date('M d, Y', strtotime($order->created)) : '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">No recent transactions</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Activity & System -->
        <div class="col-xl-4 d-flex flex-column gap-3">
            
            <!-- System Health -->
            <div class="card-sleek">
                <div class="p-2 px-3 border-bottom border-light">
                    <h6 class="card-title-sleek mb-0" style="font-size: 0.75rem;">System Health</h6>
                </div>
                <div class="p-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-micro fw-semibold">PHP Version</span>
                        <span class="text-micro fw-bold text-dark"><?= h($systemHealth['php_version']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-micro fw-semibold">CakePHP Version</span>
                        <span class="text-micro fw-bold text-dark"><?= h($systemHealth['cake_version']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-micro fw-semibold">Disk Usage</span>
                        <span class="text-micro fw-bold text-dark"><?= h($systemHealth['disk_usage']) ?> (Free: <?= h($systemHealth['disk_free']) ?>)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-micro fw-semibold">Memory Usage</span>
                        <span class="text-micro fw-bold text-dark"><?= h($systemHealth['memory_usage']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card-sleek flex-grow-1">
                <div class="p-2 px-3 border-bottom border-light">
                    <h6 class="card-title-sleek mb-0" style="font-size: 0.75rem;">Audit Log</h6>
                </div>
                <div class="p-0">
                    <ul class="list-group list-group-flush" style="font-size: 0.8rem;">
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                            <li class="list-group-item px-3 py-2 border-0 border-bottom border-light d-flex flex-column">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold text-dark"><?= h($activity->user->username ?? 'System') ?></span>
                                    <span class="text-micro text-muted"><?= $activity->created ? date('H:i', strtotime($activity->created)) : '-' ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-sleek bg-primary bg-opacity-10 text-primary" style="font-size:0.65rem;"><?= h($activity->action) ?></span>
                                    <span class="text-muted text-truncate"><?= h($activity->description) ?></span>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-center text-muted py-3">No recent activity</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Restored Legacy Features Row -->
    <div class="row g-3 mb-3">
        <!-- Online Users -->
        <div class="col-xl-6">
            <div class="card-sleek h-100">
                <div class="p-2 px-3 border-bottom border-light">
                    <h6 class="card-title-sleek mb-0" style="font-size: 0.75rem;"><i class="fa-solid fa-users text-success me-2"></i>Online Users (Active Now)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sleek table-hover table-borderless">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>IP Address</th>
                                <th>Last Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($onlineUsers)): ?>
                                <?php foreach ($onlineUsers as $ou): ?>
                                <tr>
                                    <td class="fw-semibold text-dark"><?= h($ou->username) ?></td>
                                    <td class="text-muted font-monospace text-micro"><?= h($ou->last_login_ip ?? '-') ?></td>
                                    <td class="text-muted"><?= $ou->last_login_time ? $ou->last_login_time->timeAgoInWords() : '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted py-3">No users currently online.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Contact Enquiries -->
        <div class="col-xl-6">
            <div class="card-sleek h-100">
                <div class="d-flex justify-content-between align-items-center p-2 px-3 border-bottom border-light">
                    <h6 class="card-title-sleek mb-0" style="font-size: 0.75rem;"><i class="fa-solid fa-envelope text-warning me-2"></i>Recent Contact Enquiries</h6>
                    <a href="#" class="btn btn-sm btn-light border py-0" style="font-size: 0.65rem;">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sleek table-hover table-borderless">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentEnquiriesList)): ?>
                                <?php foreach ($recentEnquiriesList as $enquiry): ?>
                                <tr>
                                    <td class="fw-semibold text-dark"><?= h($enquiry->name) ?></td>
                                    <td class="text-muted text-truncate" style="max-width: 150px;"><?= h($enquiry->subject) ?></td>
                                    <td>
                                        <?php if ($enquiry->reply_status): ?>
                                            <span class="badge badge-sleek bg-success bg-opacity-10 text-success">Replied</span>
                                        <?php else: ?>
                                            <span class="badge badge-sleek bg-warning bg-opacity-10 text-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted py-3">No enquiries yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.scale.grid.color = '#f1f5f9';
    
    // Revenue Chart (Bar)
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
            datasets: [{
                data: [12, 19, 14, 28, 22, 35, 42],
                backgroundColor: '#10b981',
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: { grid: { display: false } }
            }
        }
    });

    // Traffic Chart (Line)
    const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
    new Chart(ctxTraffic, {
        type: 'line',
        data: {
            labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
            datasets: [{
                data: [120, 190, 150, 220, 180, 250, 310],
                borderColor: '#4f46e5',
                borderWidth: 2,
                tension: 0.4,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
