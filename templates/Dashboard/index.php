<?php $this->assign('title', 'Dashboard'); ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Enterprise Dashboard</h4>
        <p class="text-muted mb-0">Welcome back, <strong><?= h($currentUser->username ?? 'Admin') ?></strong> — <?= date('l, F j, Y') ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['controller' => 'Reports', 'action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-chart-bar me-1"></i> Reports
        </a>
        <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'clearCache']) ?>" class="btn btn-sm btn-outline-warning">
            <i class="fa-solid fa-rotate me-1"></i> Refresh Cache
        </a>
    </div>
</div>

<!-- KPI Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 p-3" style="background: rgba(99,102,241,0.15);">
                    <i class="fa-solid fa-users fa-lg" style="color: #6366f1;"></i>
                </div>
                <span class="badge bg-success bg-opacity-15 text-success small">Active</span>
            </div>
            <h2 class="fw-bold mb-1"><?= number_format($totalUsers ?? 0) ?></h2>
            <p class="text-muted mb-0 small">Total Users</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 p-3" style="background: rgba(16,185,129,0.15);">
                    <i class="fa-solid fa-cart-shopping fa-lg" style="color: #10b981;"></i>
                </div>
                <span class="badge bg-warning bg-opacity-15 text-warning small">Live</span>
            </div>
            <h2 class="fw-bold mb-1"><?= number_format($totalOrders ?? 0) ?></h2>
            <p class="text-muted mb-0 small">Total Orders</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 p-3" style="background: rgba(245,158,11,0.15);">
                    <i class="fa-solid fa-box fa-lg" style="color: #f59e0b;"></i>
                </div>
                <span class="badge bg-info bg-opacity-15 text-info small">Catalog</span>
            </div>
            <h2 class="fw-bold mb-1"><?= number_format($totalProducts ?? 0) ?></h2>
            <p class="text-muted mb-0 small">Products</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 p-3" style="background: rgba(239,68,68,0.15);">
                    <i class="fa-solid fa-envelope-open-text fa-lg" style="color: #ef4444;"></i>
                </div>
                <span class="badge bg-danger bg-opacity-15 text-danger small">Pending</span>
            </div>
            <h2 class="fw-bold mb-1"><?= number_format($pendingEnquiries ?? 0) ?></h2>
            <p class="text-muted mb-0 small">Enquiries</p>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-4 mb-4">
    <!-- Recent Orders -->
    <div class="col-xl-8">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom" style="border-color: var(--border-color) !important;">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-cart-shopping text-success me-2"></i>Recent Orders</h6>
                <a href="<?= $this->Url->build(['controller' => 'Orders', 'action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr class="text-muted" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px;">
                            <th class="py-3 ps-4">Order #</th>
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
                                <td class="ps-4 fw-bold text-primary">#<?= h($order->order_number ?? $order->id) ?></td>
                                <td><?= h($order->user->username ?? 'Guest') ?></td>
                                <td class="fw-bold">₹<?= number_format($order->total ?? 0, 2) ?></td>
                                <td>
                                    <?php
                                    $statusColors = ['Pending'=>'warning','Processing'=>'info','Shipped'=>'primary','Delivered'=>'success','Cancelled'=>'danger'];
                                    $sc = $statusColors[$order->status ?? 'Pending'] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $sc ?> bg-opacity-20 text-<?= $sc ?>"><?= h($order->status ?? 'Pending') ?></span>
                                </td>
                                <td class="text-muted small"><?= $order->created ? date('M d, Y', strtotime($order->created)) : '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4"><i class="fa-solid fa-inbox me-2"></i>No orders yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Users + System Status -->
    <div class="col-xl-4">
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom" style="border-color: var(--border-color) !important;">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-users text-primary me-2"></i>New Users</h6>
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary">All Users</a>
            </div>
            <div class="p-3">
                <?php if (!empty($recentUsers)): ?>
                    <?php foreach ($recentUsers as $user): ?>
                    <div class="d-flex align-items-center gap-3 py-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" 
                             style="width:38px;height:38px;background:rgba(99,102,241,0.2);color:#6366f1;font-size:0.9rem;">
                            <?= strtoupper(substr($user->username ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold text-truncate"><?= h($user->username) ?></div>
                            <div class="text-muted small text-truncate"><?= h($user->email) ?></div>
                        </div>
                        <span class="badge <?= $user->is_active ? 'bg-success' : 'bg-secondary' ?> bg-opacity-20 <?= $user->is_active ? 'text-success' : 'text-secondary' ?> flex-shrink-0">
                            <?= $user->is_active ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">No users yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Health -->
        <div class="glass-card">
            <div class="p-4 border-bottom" style="border-color: var(--border-color) !important;">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-server text-warning me-2"></i>System Health</h6>
            </div>
            <div class="p-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">Plugins</span>
                    <span class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>8 Active</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">REST API (v1)</span>
                    <span class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>Online</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">Queue Worker</span>
                    <span class="text-warning small fw-semibold"><i class="fa-solid fa-clock me-1"></i>Idle</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">Cache (Redis)</span>
                    <span class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>Connected</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">PHP Memory</span>
                    <span class="text-info small fw-semibold"><?= round(memory_get_usage(true) / 1048576, 1) ?> MB</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row g-4">
    <div class="col-12">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom" style="border-color: var(--border-color) !important;">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left text-info me-2"></i>Recent Activity Log</h6>
                <a href="<?= $this->Url->build(['controller' => 'ActivityLogs', 'action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary">View Full Log</a>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr class="text-muted" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px;">
                            <th class="py-3 ps-4">User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold"><?= h($activity->user->username ?? 'System') ?></span>
                                </td>
                                <td><span class="badge bg-primary bg-opacity-20 text-primary"><?= h($activity->action) ?></span></td>
                                <td class="text-muted"><?= h($activity->description) ?></td>
                                <td class="text-muted small font-monospace"><?= h($activity->ip_address ?? '-') ?></td>
                                <td class="text-muted small"><?= $activity->created ? date('M d H:i', strtotime($activity->created)) : '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4"><i class="fa-solid fa-inbox me-2"></i>No activity recorded yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
