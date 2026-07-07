<?php
$this->assign('title', 'System Dashboard');
?>

<div class="row g-4 mb-4">
    <!-- Statistic Widgets -->
    <div class="col-md-3">
        <div class="widget-card glass-card">
            <div>
                <h6 class="text-muted mb-1">Today's Logins</h6>
                <h3 class="fw-bold text-white mb-0"><?= h($todayLoginCount) ?></h3>
            </div>
            <div class="widget-icon bg-primary bg-opacity-20 text-primary">
                <i class="fa-solid fa-right-to-bracket"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="widget-card glass-card">
            <div>
                <h6 class="text-muted mb-1">Online Users</h6>
                <h3 class="fw-bold text-white mb-0"><?= h($onlineUsersCount) ?></h3>
            </div>
            <div class="widget-icon bg-success bg-opacity-20 text-success">
                <i class="fa-solid fa-signal"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="widget-card glass-card">
            <div>
                <h6 class="text-muted mb-1">New Registrations</h6>
                <h3 class="fw-bold text-white mb-0"><?= h($newRegistrationsCount) ?></h3>
            </div>
            <div class="widget-icon bg-info bg-opacity-20 text-info">
                <i class="fa-solid fa-user-plus"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="widget-card glass-card">
            <div>
                <h6 class="text-muted mb-1">Pending Verification</h6>
                <h3 class="fw-bold text-white mb-0"><?= h($pendingVerificationCount) ?></h3>
            </div>
            <div class="widget-icon bg-warning bg-opacity-20 text-warning">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Charts section -->
    <div class="col-lg-8">
        <div class="card glass-card p-4 h-100">
            <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-chart-line text-primary me-2"></i>Registration & Logins</h5>
            <div style="position: relative; height: 300px;">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="col-lg-4">
        <div class="card glass-card p-4 h-100">
            <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-server text-info me-2"></i>System Health</h5>
            <div class="d-flex justify-content-between border-bottom border-secondary py-2">
                <span class="text-muted">PHP Version</span>
                <span class="fw-bold text-white"><?= h($systemHealth['php_version']) ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom border-secondary py-2">
                <span class="text-muted">CakePHP Version</span>
                <span class="fw-bold text-white"><?= h($systemHealth['cake_version']) ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom border-secondary py-2">
                <span class="text-muted">Disk Usage</span>
                <span class="fw-bold text-white"><?= h($systemHealth['disk_usage']) ?> (Free: <?= h($systemHealth['disk_free']) ?>)</span>
            </div>
            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Memory Usage</span>
                <span class="fw-bold text-white"><?= h($systemHealth['memory_usage']) ?></span>
            </div>
            
            <div class="mt-4 text-center">
                <button class="btn btn-primary btn-sm w-100" onclick="window.location.reload()"><i class="fa-solid fa-sync me-1"></i> Refresh System metrics</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Online Users -->
    <div class="col-md-6">
        <div class="card glass-card p-4">
            <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-users text-success me-2"></i>Online Users (Active Now)</h5>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>IP Address</th>
                            <th>Last Activity</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($onlineUsers) === 0): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No users online.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($onlineUsers as $ou): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                                            <span><?= h($ou->username) ?></span>
                                        </div>
                                    </td>
                                    <td><?= h($ou->last_login_ip ?: '127.0.0.1') ?></td>
                                    <td><?= $ou->last_login_time ? h($ou->last_login_time->timeAgoInWords()) : 'N/A' ?></td>
                                    <td class="text-end">
                                        <?= $this->Form->postLink(
                                            '<i class="fa-solid fa-user-slash"></i> Force Logout',
                                            ['action' => 'forceLogout', $ou->id],
                                            [
                                                'escapeTitle' => false,
                                                'class' => 'btn btn-outline-danger btn-sm',
                                                'confirm' => __('Are you sure you want to force logout {0}?', $ou->username)
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<i class="fa-solid fa-ban"></i> Lock',
                                            ['action' => 'forceLogout', $ou->id],
                                            [
                                                'escapeTitle' => false,
                                                'data' => ['inactivate' => '1'],
                                                'class' => 'btn btn-danger btn-sm ms-1',
                                                'confirm' => __('Are you sure you want to logout and deactivate {0}?', $ou->username)
                                            ]
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Enquiries -->
    <div class="col-md-6">
        <div class="card glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-white m-0"><i class="fa-solid fa-circle-question text-warning me-2"></i>Recent Contact Enquiries</h5>
                <a href="<?= $this->Url->build('/contact-enquiries') ?>" class="btn btn-outline-primary btn-sm">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($recentEnquiries) === 0): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No enquiries yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentEnquiries as $enquiry): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= h($enquiry->name) ?></div>
                                        <div class="text-muted small"><?= h($enquiry->email) ?></div>
                                    </td>
                                    <td><?= h($enquiry->subject) ?></td>
                                    <td>
                                        <?php if ($enquiry->reply_status === 'replied'): ?>
                                            <span class="badge badge-active bg-success bg-opacity-20 text-success">Replied</span>
                                        <?php else: ?>
                                            <span class="badge badge-inactive bg-warning bg-opacity-20 text-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Latest Activities -->
    <div class="col-12">
        <div class="card glass-card p-4">
            <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-clock-rotate-left text-info me-2"></i>Latest System Activities</h5>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($latestActivities) === 0): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No activity logged.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($latestActivities as $act): ?>
                                <tr>
                                    <td><?= $act->user ? h($act->user->username) : 'Guest/System' ?></td>
                                    <td><span class="badge bg-secondary"><?= h($act->action) ?></span></td>
                                    <td><?= h($act->description) ?></td>
                                    <td><?= h($act->ip_address) ?></td>
                                    <td><?= $act->created->format('Y-m-d H:i:s') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'New Registrations',
                data: [12, 19, 3, 5, 2, 3, 9],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'User Logins',
                data: [45, 62, 24, 75, 41, 56, 85],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#94a3b8' }
                }
            },
            scales: {
                y: {
                    grid: { color: '#334155' },
                    ticks: { color: '#94a3b8' }
                },
                x: {
                    grid: { color: '#334155' },
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });
});
</script>
