<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CakePHP LoadTesting Plugin</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --sidebar-bg: #1e2535;
            --sidebar-hover: #2a3449;
            --sidebar-active: #3b82f6;
            --bg-main: #f4f6f8;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --primary: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: #fff;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #111827;
            overflow-y: auto;
        }
        
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        
        .sidebar-brand {
            padding: 24px;
            font-size: 1.25rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .nav-category {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            padding: 20px 24px 8px;
        }

        .sidebar-nav-item {
            padding: 10px 24px;
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar-nav-item:hover { color: #fff; background-color: rgba(255,255,255,0.03); }
        .sidebar-nav-item.active { color: #fff; background-color: var(--sidebar-active); border-radius: 0 24px 24px 0; margin-right: 16px; box-shadow: 0 4px 6px -1px rgba(59,130,246,0.3); }

        .plugin-info-card {
            margin: 24px 16px;
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 8px;
            padding: 16px;
            font-size: 0.75rem;
            color: #94a3b8;
        }
        
        .plugin-info-card ul { list-style: none; padding: 0; margin: 12px 0; }
        .plugin-info-card li { margin-bottom: 8px; }
        
        .toggle-switch {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.05);
            margin-top: 12px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            position: relative;
        }
        
        /* Top Header */
        .top-header {
            background-color: #fff;
            padding: 12px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .header-search {
            background: var(--bg-main);
            border-radius: 8px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            width: 300px;
        }
        
        .header-search input {
            border: none;
            background: transparent;
            outline: none;
            margin-left: 8px;
            font-size: 0.85rem;
            width: 100%;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-profile img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Content Area */
        .content-area {
            padding: 24px 32px;
            flex: 1;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }

        /* Global Footer */
        .global-footer {
            background: var(--sidebar-bg);
            color: #94a3b8;
            padding: 12px 32px;
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            border-top: 1px solid #111827;
        }
        .global-footer a { color: #94a3b8; text-decoration: none; margin-left: 12px; }
        .global-footer a:hover { color: #fff; }

        /* Shared Dashboard Components */
        .glass-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            padding: 20px;
        }
        
        .card-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <!-- Left Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-server text-primary"></i>
            <div>
                CakePHP<br>
                <span style="font-size:0.6rem; font-weight:500; color:#94a3b8; text-transform:uppercase;">LoadTesting Plugin</span>
            </div>
        </div>
        
        <nav class="mt-3 pb-4">
            <?php $curr = $this->request->getParam('controller'); ?>
            <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'Dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-house" style="width:20px;"></i> Dashboard
            </a>
            
            <div class="nav-category">Load Testing</div>
            <a href="<?= $this->Url->build(['controller' => 'LoadTests', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'LoadTests' ? 'active' : '' ?>"><i class="fa-solid fa-bolt" style="width:20px;"></i> Load Tests</a>
            <a href="<?= $this->Url->build(['controller' => 'TestScenarios', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'TestScenarios' ? 'active' : '' ?>"><i class="fa-solid fa-layer-group" style="width:20px;"></i> Test Scenarios</a>
            <a href="<?= $this->Url->build(['controller' => 'TestSuites', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'TestSuites' ? 'active' : '' ?>"><i class="fa-solid fa-boxes-stacked" style="width:20px;"></i> Test Suites</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-bolt-lightning" style="width:20px;"></i> Spike Tests</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-fire" style="width:20px;"></i> Stress Tests</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-clock" style="width:20px;"></i> Soak / Endurance Tests</a>
            <a href="<?= $this->Url->build(['controller' => 'UnitTests', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'UnitTests' ? 'active' : '' ?>"><i class="fa-solid fa-vial" style="width:20px;"></i> Unit Testing (PHPUnit)</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-link" style="width:20px;"></i> Integration Testing</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-globe" style="width:20px;"></i> API Testing</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-database" style="width:20px;"></i> Database Performance</a>
            
            <div class="nav-category">Analytics</div>
            <a href="<?= $this->Url->build(['controller' => 'Reports', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'Reports' ? 'active' : '' ?>"><i class="fa-solid fa-chart-line" style="width:20px;"></i> Reports</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-desktop" style="width:20px;"></i> Real-time Monitoring</a>
            <a href="<?= $this->Url->build(['controller' => 'Logs', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'Logs' ? 'active' : '' ?>"><i class="fa-solid fa-terminal" style="width:20px;"></i> Logs</a>
            <a href="<?= $this->Url->build(['controller' => 'Assertions', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'Assertions' ? 'active' : '' ?>"><i class="fa-solid fa-check-double" style="width:20px;"></i> Assertions</a>
            
            <div class="nav-category">Configuration</div>
            <a href="<?= $this->Url->build(['controller' => 'Endpoints', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'Endpoints' ? 'active' : '' ?>"><i class="fa-solid fa-network-wired" style="width:20px;"></i> Endpoints</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-sliders" style="width:20px;"></i> Test Configurations</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-users" style="width:20px;"></i> Users & Roles</a>
            <a href="<?= $this->Url->build(['controller' => 'Settings', 'action' => 'index']) ?>" class="sidebar-nav-item <?= $curr === 'Settings' ? 'active' : '' ?>"><i class="fa-solid fa-gear" style="width:20px;"></i> Settings</a>

            <div class="nav-category">System</div>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-calendar-alt" style="width:20px;"></i> Scheduler (Cron)</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-solid fa-envelope" style="width:20px;"></i> Email Notifications</a>
            <a href="#" class="sidebar-nav-item"><i class="fa-regular fa-circle-question" style="width:20px;"></i> Help & Docs</a>
        </nav>
        
        <div class="mt-auto"></div>
        <div class="plugin-info-card">
            <div class="d-flex align-items-center gap-2 text-white fw-bold mb-2">
                <i class="fa-solid fa-layer-group text-danger"></i> Plugin Information
            </div>
            <ul>
                <li>Version: 1.0.0</li>
                <li>CakePHP: 3.x, 4.x, 5.x</li>
                <li>PHP: 7.4+ / 8.x</li>
            </ul>
            <div class="toggle-switch">
                <span>Developer Mode</span>
                <div class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" checked style="background-color: var(--success); border-color: var(--success);">
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="main-content">
        
        <!-- Top Header -->
        <header class="top-header">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-bars fs-5 text-muted cursor-pointer"></i>
                <h4 class="m-0 fw-bold d-flex align-items-center gap-2">
                    Dashboard <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" style="font-size:0.6rem; font-weight:600;">System Overview</span>
                </h4>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <div class="header-search">
                    <i class="fa-solid fa-search text-muted"></i>
                    <input type="text" placeholder="Search anything...">
                </div>
                
                <i class="fa-regular fa-moon fs-5 text-muted cursor-pointer"></i>
                
                <div class="position-relative cursor-pointer">
                    <i class="fa-regular fa-bell fs-5 text-muted"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width:10px; height:10px;"></span>
                </div>
                
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name=Amit+Ghosh&background=3b82f6&color=fff" onerror="this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2236%22%20height%3D%2236%22%20viewBox%3D%220%200%2036%2036%22%3E%3Crect%20width%3D%2236%22%20height%3D%2236%22%20fill%3D%22%233b82f6%22%2F%3E%3Ctext%20x%3D%2218%22%20y%3D%2223%22%20font-family%3D%22Arial%22%20font-size%3D%2216%22%20fill%3D%22%23fff%22%20text-anchor%3D%22middle%22%3EAG%3C%2Ftext%3E%3C%2Fsvg%3E'" alt="User">
                    <div style="line-height:1.2;">
                        <div class="fw-bold" style="font-size:0.8rem;">Amit Ghosh</div>
                        <div class="text-muted" style="font-size:0.7rem;">Administrator <i class="fa-solid fa-chevron-down ms-1"></i></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="content-area">
            <?= $this->fetch('content') ?>
        </div>
        
        <!-- Global Footer -->
        <footer class="global-footer">
            <div>CakePHP LoadTesting Plugin v1.0.0</div>
            <div>Built with <i class="fa-solid fa-heart text-danger mx-1"></i> for CakePHP Developers</div>
            <div>
                <a href="#">Documentation</a> | 
                <a href="#">Support</a> | 
                <a href="#">GitHub</a>
            </div>
        </footer>
    </div>

</body>
</html>
