<style>
    /* Dashboard Specific Styles */
    .kpi-card {
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
    }
    .kpi-icon-wrap {
        width: 40px; height: 40px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .kpi-title { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
    .kpi-value { font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
    
    .trend-up { color: var(--success); font-size: 0.75rem; font-weight: 600; }
    .trend-down { color: var(--danger); font-size: 0.75rem; font-weight: 600; }
    
    .btn-create {
        background: var(--primary);
        color: #fff;
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    
    .action-list-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 0; border-bottom: 1px solid var(--border);
        text-decoration: none; color: var(--text-main);
    }
    .action-list-item:last-child { border-bottom: none; padding-bottom: 0; }
    
    .action-icon {
        width: 32px; height: 32px; border-radius: 6px; background: rgba(59,130,246,0.1); color: var(--primary);
        display: flex; align-items: center; justify-content: center;
    }
    
    .table-clean { font-size: 0.8rem; margin: 0; }
    .table-clean th { color: var(--text-muted); font-weight: 600; border-bottom: 1px solid var(--border); padding: 12px 8px; }
    .table-clean td { padding: 12px 8px; vertical-align: middle; border-bottom: 1px solid var(--border); }
    .table-clean tr:last-child td { border-bottom: none; }
    
    .status-badge {
        padding: 4px 10px; border-radius: 12px; font-size: 0.7rem; font-weight: 600;
    }
    .bg-passed { background: #dcfce7; color: #16a34a; }
    .bg-failed { background: #fee2e2; color: #dc2626; }
    
    .sys-res-label { display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 600; margin-bottom: 6px; }
    .sys-res-bar { height: 6px; border-radius: 3px; background: var(--border); margin-bottom: 16px; overflow: hidden; }
    .sys-res-fill { height: 100%; border-radius: 3px; }
    
    .stepper-wrap { display: flex; justify-content: space-between; position: relative; margin: 20px 0; }
    .stepper-item { display: flex; flex-direction: column; align-items: center; text-align: center; width: 18%; z-index: 2; background: #fff; }
    .stepper-circle { width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; color: var(--primary); }
    .stepper-line { position: absolute; top: 20px; left: 10%; right: 10%; height: 2px; background: var(--border); z-index: 1; }
    
    .notification-item { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); }
    .notification-item:last-child { border-bottom: none; }
</style>

<div class="row g-4">
    <!-- Main Left Column (9 cols) -->
    <div class="col-xl-9 col-lg-8">
        
        <!-- ROW 1: KPIs -->
        <div class="row g-3 mb-4">
            <div class="col">
                <div class="glass-card kpi-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background:#eff6ff; color:#3b82f6;"><i class="fa-solid fa-snowflake"></i></div>
                        <div>
                            <div class="kpi-title text-nowrap">Total Tests</div>
                            <div class="kpi-value">156</div>
                            <div class="trend-up text-nowrap"><i class="fa-solid fa-arrow-up"></i> 18.6% <span class="text-muted fw-normal">from last 7 days</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="glass-card kpi-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background:#dcfce7; color:#10b981;"><i class="fa-regular fa-circle-check"></i></div>
                        <div>
                            <div class="kpi-title text-nowrap">Tests Executed</div>
                            <div class="kpi-value">89</div>
                            <div class="trend-up text-nowrap"><i class="fa-solid fa-arrow-up"></i> 22.4% <span class="text-muted fw-normal">from last 7 days</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="glass-card kpi-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background:#f3e8ff; color:#a855f7;"><i class="fa-solid fa-shield-halved"></i></div>
                        <div>
                            <div class="kpi-title text-nowrap">Success Rate</div>
                            <div class="kpi-value">98.32%</div>
                            <div class="trend-up text-nowrap"><i class="fa-solid fa-arrow-up"></i> 2.7% <span class="text-muted fw-normal">from last 7 days</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="glass-card kpi-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background:#fef3c7; color:#f59e0b;"><i class="fa-regular fa-clock"></i></div>
                        <div>
                            <div class="kpi-title text-nowrap">Avg Response</div>
                            <div class="kpi-value">245 ms</div>
                            <div class="trend-up text-nowrap"><i class="fa-solid fa-arrow-down"></i> 18 ms <span class="text-muted fw-normal">from 7d</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="glass-card kpi-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon-wrap" style="background:#e0f2fe; color:#0ea5e9;"><i class="fa-solid fa-sliders"></i></div>
                        <div>
                            <div class="kpi-title text-nowrap">Reqs / Sec</div>
                            <div class="kpi-value">152.7</div>
                            <div class="trend-up text-nowrap"><i class="fa-solid fa-arrow-up"></i> 23.1 <span class="text-muted fw-normal">from 7d</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 2: Charts -->
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="glass-card h-100">
                    <div class="card-title">
                        Load Test Performance
                        <select class="form-select form-select-sm" style="width:130px; font-size:0.75rem;"><option>Last 24 Hours</option></select>
                    </div>
                    <div style="position: relative; height: 250px; width: 100%;">
                        <canvas id="perfChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card h-100">
                    <div class="card-title">Test Status Overview <select class="form-select form-select-sm" style="width:90px; font-size:0.75rem;"><option>All Status</option></select></div>
                    <div class="d-flex align-items-center justify-content-center mt-3 position-relative" style="height: 160px;">
                        <div style="position: absolute; width: 160px; height: 160px;"><canvas id="statusChart"></canvas></div>
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center; z-index: 10;">
                            <div style="font-size:0.7rem; color:var(--text-muted);">Total</div>
                            <div style="font-size:1.5rem; font-weight:800;">156</div>
                        </div>
                    </div>
                    <div class="mt-4" style="font-size:0.75rem;">
                        <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-success me-1"></i> Passed</span> <span>142 (91.0%)</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-danger me-1"></i> Failed</span> <span>6 (3.8%)</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-warning me-1"></i> Running</span> <span>5 (3.2%)</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-secondary me-1"></i> Pending</span> <span>3 (1.9%)</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 3: Tables & Resources -->
        <div class="row g-4 mb-4">
            <div class="col-md-7">
                <div class="glass-card h-100">
                    <div class="card-title">Recent Tests</div>
                    <table class="table table-clean w-100">
                        <thead>
                            <tr><th>Test Name</th><th>Type</th><th>Users</th><th>Duration</th><th>Status</th><th>Started At</th></tr>
                        </thead>
                        <tbody>
                            <tr><td class="text-primary fw-bold">User Login Load Test</td><td>Load Test</td><td>100</td><td>10 min</td><td><span class="status-badge bg-passed">Passed</span></td><td>2 min ago</td></tr>
                            <tr><td class="text-primary fw-bold">API Stress Test</td><td>Stress Test</td><td>200</td><td>15 min</td><td><span class="status-badge bg-passed">Passed</span></td><td>15 min ago</td></tr>
                            <tr><td class="text-primary fw-bold">Checkout Flow Test</td><td>Load Test</td><td>150</td><td>12 min</td><td><span class="status-badge bg-passed">Passed</span></td><td>1 hour ago</td></tr>
                            <tr><td class="text-primary fw-bold">Product Search Test</td><td>Spike Test</td><td>300</td><td>5 min</td><td><span class="status-badge bg-failed">Failed</span></td><td>2 hours ago</td></tr>
                            <tr><td class="text-primary fw-bold">Endurance Test</td><td>Soak Test</td><td>50</td><td>60 min</td><td><span class="status-badge bg-passed">Passed</span></td><td>3 hours ago</td></tr>
                        </tbody>
                    </table>
                    <div class="mt-3"><button class="btn btn-outline-primary btn-sm rounded-pill px-3" style="font-size:0.75rem;">View All Tests</button></div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="glass-card h-100">
                    <div class="card-title">System Resources</div>
                    <div class="mt-3">
                        <div class="sys-res-label"><span>CPU Usage</span> <span>45%</span></div>
                        <div class="sys-res-bar"><div class="sys-res-fill bg-success" style="width: 45%;"></div></div>
                        
                        <div class="sys-res-label"><span>Memory Usage</span> <span>62%</span></div>
                        <div class="sys-res-bar"><div class="sys-res-fill bg-primary" style="width: 62%;"></div></div>
                        
                        <div class="sys-res-label"><span>Disk Usage</span> <span>38%</span></div>
                        <div class="sys-res-bar"><div class="sys-res-fill" style="background:#8b5cf6; width: 38%;"></div></div>
                        
                        <div class="sys-res-label"><span>Network I/O</span> <span>72%</span></div>
                        <div class="sys-res-bar"><div class="sys-res-fill bg-warning" style="width: 72%;"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 4: Flow & Real-time -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="glass-card h-100">
                    <div class="card-title">Test Execution Flow</div>
                    <div class="stepper-wrap">
                        <div class="stepper-line"></div>
                        <div class="stepper-item"><div class="stepper-circle bg-primary bg-opacity-10"><i class="fa-solid fa-sliders"></i></div><div class="fw-bold" style="font-size:0.75rem;">1. Setup Test</div><div class="text-muted" style="font-size:0.6rem;">Configure endpoints...</div></div>
                        <div class="stepper-item"><div class="stepper-circle bg-primary bg-opacity-10"><i class="fa-solid fa-users"></i></div><div class="fw-bold" style="font-size:0.75rem;">2. Configure Load</div><div class="text-muted" style="font-size:0.6rem;">Set virtual users...</div></div>
                        <div class="stepper-item"><div class="stepper-circle bg-primary bg-opacity-10"><i class="fa-solid fa-play"></i></div><div class="fw-bold" style="font-size:0.75rem;">3. Execute Test</div><div class="text-muted" style="font-size:0.6rem;">Run test with real-time...</div></div>
                        <div class="stepper-item"><div class="stepper-circle bg-primary bg-opacity-10"><i class="fa-solid fa-chart-bar"></i></div><div class="fw-bold" style="font-size:0.75rem;">4. Collect Metrics</div><div class="text-muted" style="font-size:0.6rem;">Collect performance...</div></div>
                        <div class="stepper-item"><div class="stepper-circle bg-primary bg-opacity-10"><i class="fa-solid fa-file-invoice"></i></div><div class="fw-bold" style="font-size:0.75rem;">5. Analyze Results</div><div class="text-muted" style="font-size:0.6rem;">Generate reports...</div></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card h-100 text-center">
                    <div class="card-title text-start">Real-time Requests</div>
                    <div style="position:relative; height:120px; display:flex; align-items:center; justify-content:center;">
                        <div style="position: absolute; width: 100%; height: 100%;"><canvas id="gaugeChart"></canvas></div>
                        <div style="position:absolute; bottom:10px; z-index: 10;">
                            <div style="font-size:1.5rem; font-weight:800;">153</div>
                            <div style="font-size:0.7rem; color:var(--text-muted);">Requests / Sec</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card h-100">
                    <div class="card-title">Error Rate</div>
                    <div style="font-size:2rem; font-weight:800;">0.64%</div>
                    <div class="text-muted mb-3" style="font-size:0.8rem;">Error Rate</div>
                    <div style="position: relative; height: 50px; width: 100%;">
                        <canvas id="errorSparkline"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 5: Bottom Data -->
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="glass-card h-100">
                    <div class="card-title">Recent Test Runs</div>
                    <table class="table table-clean w-100">
                        <thead>
                            <tr><th>#</th><th>Test Name</th><th>Type</th><th>Users</th><th>Duration</th><th>Status</th><th>Success Rate</th><th>Avg Response Time</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td class="text-primary fw-bold">User Login Load Test</td><td>Load Test</td><td>100</td><td>10 min</td><td><span class="status-badge bg-passed">Passed</span></td><td>100%</td><td>215 ms</td><td><i class="fa-solid fa-eye text-muted me-2"></i><i class="fa-solid fa-pen text-muted"></i></td></tr>
                            <tr><td>2</td><td class="text-primary fw-bold">API Stress Test</td><td>Stress Test</td><td>200</td><td>15 min</td><td><span class="status-badge bg-passed">Passed</span></td><td>99.5%</td><td>312 ms</td><td><i class="fa-solid fa-eye text-muted me-2"></i><i class="fa-solid fa-pen text-muted"></i></td></tr>
                            <tr><td>3</td><td class="text-primary fw-bold">Checkout Flow Test</td><td>Load Test</td><td>150</td><td>12 min</td><td><span class="status-badge bg-passed">Passed</span></td><td>98.7%</td><td>245 ms</td><td><i class="fa-solid fa-eye text-muted me-2"></i><i class="fa-solid fa-pen text-muted"></i></td></tr>
                            <tr><td>4</td><td class="text-primary fw-bold">Product Search Test</td><td>Spike Test</td><td>300</td><td>5 min</td><td><span class="status-badge bg-failed">Failed</span></td><td>85.2%</td><td>512 ms</td><td><i class="fa-solid fa-eye text-muted me-2"></i><i class="fa-solid fa-pen text-muted"></i></td></tr>
                            <tr><td>5</td><td class="text-primary fw-bold">Endurance Test</td><td>Soak Test</td><td>50</td><td>60 min</td><td><span class="status-badge bg-passed">Passed</span></td><td>97.8%</td><td>298 ms</td><td><i class="fa-solid fa-eye text-muted me-2"></i><i class="fa-solid fa-pen text-muted"></i></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card h-100">
                    <div class="card-title">Test Types Distribution</div>
                    <div class="d-flex align-items-center mt-3">
                        <div style="width: 50%; position: relative; height: 160px;">
                            <canvas id="typeChart"></canvas>
                        </div>
                        <div style="width: 50%; font-size:0.75rem; padding-left:16px;">
                            <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-primary me-1"></i> Load Test</span> <span>45</span></div>
                            <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-info me-1"></i> Stress Test</span> <span>38</span></div>
                            <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-success me-1"></i> Spike Test</span> <span>25</span></div>
                            <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-warning me-1"></i> Soak Test</span> <span>20</span></div>
                            <div class="d-flex justify-content-between mb-2"><span class="fw-bold"><i class="fa-solid fa-square text-secondary me-1"></i> API Test</span> <span>18</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Right Sidebar (3 cols) -->
    <div class="col-xl-3 col-lg-4">
        
        <!-- Controls -->
        <div class="d-flex align-items-center gap-3 mb-4 justify-content-end">
            <div class="bg-white border rounded px-3 py-2 text-muted fw-bold" style="font-size:0.8rem;"><i class="fa-regular fa-calendar me-2"></i> May 18 - May 24, 2025</div>
            <button class="btn btn-outline-primary bg-white text-primary fw-bold px-4">Refresh</button>
        </div>
        
        <div class="glass-card mb-4 border-danger bg-danger bg-opacity-10 text-danger" style="padding:16px;">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon-wrap bg-white text-danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <div style="font-size:0.75rem; font-weight:700;">Failed Tests</div>
                    <div style="font-size:1.5rem; font-weight:800; line-height:1;">2</div>
                    <div style="font-size:0.7rem; font-weight:600;"><i class="fa-solid fa-arrow-down"></i> 60% from last 7 days</div>
                </div>
            </div>
        </div>
        
        <button class="btn-create mb-4"><i class="fa-solid fa-plus"></i> Create New Test</button>
        
        <!-- Quick Actions -->
        <div class="glass-card mb-4">
            <div class="card-title">Quick Actions</div>
            <a href="#" class="action-list-item">
                <div class="action-icon"><i class="fa-solid fa-file-signature"></i></div>
                <div style="flex:1;">
                    <div class="fw-bold" style="font-size:0.8rem;">Create Load Test</div>
                    <div class="text-muted" style="font-size:0.7rem;">Configure a new load test</div>
                </div>
                <i class="fa-solid fa-chevron-right text-muted" style="font-size:0.7rem;"></i>
            </a>
            <a href="#" class="action-list-item">
                <div class="action-icon text-success" style="background:#dcfce7;"><i class="fa-solid fa-play"></i></div>
                <div style="flex:1;">
                    <div class="fw-bold" style="font-size:0.8rem;">Run All Tests</div>
                    <div class="text-muted" style="font-size:0.7rem;">Execute all tests in queue</div>
                </div>
                <i class="fa-solid fa-chevron-right text-muted" style="font-size:0.7rem;"></i>
            </a>
            <a href="#" class="action-list-item">
                <div class="action-icon text-info" style="background:#e0f2fe;"><i class="fa-solid fa-chart-pie"></i></div>
                <div style="flex:1;">
                    <div class="fw-bold" style="font-size:0.8rem;">View Reports</div>
                    <div class="text-muted" style="font-size:0.7rem;">Generate detailed reports</div>
                </div>
                <i class="fa-solid fa-chevron-right text-muted" style="font-size:0.7rem;"></i>
            </a>
            <a href="#" class="action-list-item">
                <div class="action-icon text-warning" style="background:#fef3c7;"><i class="fa-solid fa-layer-group"></i></div>
                <div style="flex:1;">
                    <div class="fw-bold" style="font-size:0.8rem;">Test Scenarios</div>
                    <div class="text-muted" style="font-size:0.7rem;">Manage test scenarios</div>
                </div>
                <i class="fa-solid fa-chevron-right text-muted" style="font-size:0.7rem;"></i>
            </a>
        </div>
        
        <!-- Scheduled Tests -->
        <div class="glass-card mb-4">
            <div class="card-title">Upcoming Scheduled Tests</div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="fw-bold" style="font-size:0.8rem;">Nightly Load Test</div>
                    <div class="text-muted" style="font-size:0.7rem;">Today, 11:00 PM</div>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary">Load Test</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="fw-bold" style="font-size:0.8rem;">Weekend Stress Test</div>
                    <div class="text-muted" style="font-size:0.7rem;">May 24, 2025, 10:00 AM</div>
                </div>
                <span class="badge bg-info bg-opacity-10 text-info">Stress Test</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="fw-bold" style="font-size:0.8rem;">Database Performance Test</div>
                    <div class="text-muted" style="font-size:0.7rem;">May 25, 2025, 02:00 AM</div>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success">Database</span>
            </div>
            <div class="mt-3"><a href="#" class="text-primary fw-bold text-decoration-none" style="font-size:0.75rem;">View Full Schedule <i class="fa-solid fa-chevron-right ms-1" style="font-size:0.6rem;"></i></a></div>
        </div>
        
        <!-- Notifications -->
        <div class="glass-card">
            <div class="card-title">Notifications</div>
            <div class="notification-item">
                <i class="fa-solid fa-circle-check text-success mt-1"></i>
                <div style="flex:1;">
                    <div class="fw-bold text-main" style="font-size:0.75rem;">Load test completed successfully</div>
                    <div class="text-muted" style="font-size:0.7rem;">User Login Load Test</div>
                </div>
                <div class="text-muted" style="font-size:0.65rem;">2 min ago</div>
            </div>
            <div class="notification-item">
                <i class="fa-solid fa-circle-xmark text-danger mt-1"></i>
                <div style="flex:1;">
                    <div class="fw-bold text-main" style="font-size:0.75rem;">Test failed</div>
                    <div class="text-muted" style="font-size:0.7rem;">Product Search Test</div>
                </div>
                <div class="text-muted" style="font-size:0.65rem;">2 hours ago</div>
            </div>
            <div class="notification-item">
                <i class="fa-solid fa-circle-info text-primary mt-1"></i>
                <div style="flex:1;">
                    <div class="fw-bold text-main" style="font-size:0.75rem;">Scheduled test started</div>
                    <div class="text-muted" style="font-size:0.7rem;">Nightly Load Test</div>
                </div>
                <div class="text-muted" style="font-size:0.65rem;">3 hours ago</div>
            </div>
            <div class="mt-3"><a href="#" class="text-primary fw-bold text-decoration-none" style="font-size:0.75rem;">View All Notifications</a></div>
        </div>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    
    // Line Chart
    new Chart(document.getElementById('perfChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00', '24:00'],
            datasets: [
                { label: 'Requests / Sec', data: [150, 180, 160, 200, 170, 190, 210, 180, 160, 200, 150, 170, 160], borderColor: '#3b82f6', tension: 0.4, borderWidth: 2, pointRadius: 2 },
                { label: 'Avg Response Time', data: [100, 120, 90, 110, 100, 115, 95, 120, 80, 130, 90, 110, 70], borderColor: '#10b981', tension: 0.4, borderWidth: 2, pointRadius: 2 },
                { label: 'Errors', data: [10, 20, 15, 25, 10, 15, 20, 10, 25, 15, 20, 10, 15], borderColor: '#ef4444', tension: 0.4, borderWidth: 2, pointRadius: 2 }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 6, font: {size: 10} } } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: '#f1f5f9' }, beginAtZero: true }
            }
        }
    });

    // Donut Chart Status
    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Passed', 'Failed', 'Running', 'Pending'],
            datasets: [{ data: [142, 6, 5, 3], backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#94a3b8'], borderWidth: 0 }]
        },
        options: {
            cutout: '75%', responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Gauge Chart
    new Chart(document.getElementById('gaugeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            datasets: [{ data: [153, 500-153], backgroundColor: ['#10b981', '#f1f5f9'], borderWidth: 0, circumference: 180, rotation: 270 }]
        },
        options: {
            cutout: '85%', responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } }
        }
    });

    // Sparkline Error
    new Chart(document.getElementById('errorSparkline').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
            datasets: [{ data: [5, 6, 5, 7, 6, 8, 5, 9, 6, 7], borderColor: '#ef4444', borderWidth: 2, tension: 0.1, pointRadius: 0 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { display: false }, y: { display: false } }
        }
    });

    // Type Chart
    new Chart(document.getElementById('typeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Load', 'Stress', 'Spike', 'Soak', 'API'],
            datasets: [{ data: [45, 38, 25, 20, 18], backgroundColor: ['#3b82f6', '#0ea5e9', '#10b981', '#f59e0b', '#8b5cf6'], borderWidth: 0 }]
        },
        options: {
            cutout: '70%', responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
});
</script>
