<?php
$views = [
    'LoadTests' => [
        'icon' => 'fa-bolt',
        'title' => 'Load Tests',
        'btn' => 'New Load Test',
        'headers' => ['Name', 'Scenario', 'Virtual Users', 'Status', 'Started', 'Actions'],
        'rows' => [
            ['Peak Traffic Test', 'User Login Flow', '1000', '<span class="status-badge status-passed">Completed</span>', '2 hours ago'],
            ['Checkout Stress', 'Checkout Flow', '500', '<span class="status-badge status-passed">Completed</span>', 'Yesterday'],
            ['API Rate Limit', 'Get User Profile', '2000', '<span style="color:#ef4444;font-size:0.7rem;font-weight:600;padding:4px 8px;background:#fee2e2;border-radius:4px;">Failed</span>', '2 days ago'],
        ]
    ],
    'TestSuites' => [
        'icon' => 'fa-boxes-stacked',
        'title' => 'Test Suites',
        'btn' => 'New Test Suite',
        'headers' => ['Suite Name', 'Description', 'Scenarios Included', 'Last Run', 'Actions'],
        'rows' => [
            ['Core API Regression', 'Runs all core endpoints', '14', 'Today 10:00 AM'],
            ['E-Commerce Flow', 'Checkout, Cart, Payments', '5', 'Yesterday'],
            ['Nightly Stress', 'Full system load generation', '22', '3 days ago'],
        ]
    ],
    'UnitTests' => [
        'icon' => 'fa-vial',
        'title' => 'Unit Tests (PHPUnit)',
        'btn' => 'Run All Tests',
        'headers' => ['Test Class', 'Module', 'Tests', 'Assertions', 'Coverage', 'Status', 'Actions'],
        'rows' => [
            ['UsersControllerTest', 'App\Controller', '12', '45', '87%', '<span class="status-badge status-passed">Passed</span>'],
            ['OrderProcessorTest', 'App\Service', '8', '32', '95%', '<span class="status-badge status-passed">Passed</span>'],
            ['InventoryHelperTest', 'App\View\Helper', '4', '10', '100%', '<span class="status-badge status-passed">Passed</span>'],
        ]
    ],
    'Reports' => [
        'icon' => 'fa-chart-line',
        'title' => 'Test Reports',
        'btn' => 'Export All',
        'headers' => ['Report Name', 'Test Type', 'Avg Response', 'Success Rate', 'Generated On', 'Actions'],
        'rows' => [
            ['Q2 Performance Audit', 'Load Test', '145ms', '99.9%', 'July 20, 2026'],
            ['API Stress Breakdown', 'Stress Test', '800ms', '92.4%', 'July 15, 2026'],
            ['Database Bottleneck', 'Spike Test', '2.4s', '88.1%', 'July 10, 2026'],
        ]
    ],
    'Assertions' => [
        'icon' => 'fa-check-double',
        'title' => 'Global Assertions',
        'btn' => 'New Assertion',
        'headers' => ['Assertion Name', 'Target', 'Condition', 'Value', 'Severity', 'Actions'],
        'rows' => [
            ['Fast Response', 'Response Time', '&lt;', '500ms', '<span class="badge bg-danger">Critical</span>'],
            ['No Server Errors', 'Status Code', '!=', '5xx', '<span class="badge bg-danger">Critical</span>'],
            ['Contains API Version', 'JSON Body', 'hasKey', '"version"', '<span class="badge bg-warning text-dark">Warning</span>'],
        ]
    ],
    'Endpoints' => [
        'icon' => 'fa-network-wired',
        'title' => 'Monitored Endpoints',
        'btn' => 'Add Endpoint',
        'headers' => ['Path', 'Method', 'Avg Latency', 'Error Rate', 'Last Tested', 'Actions'],
        'rows' => [
            ['<span class="font-monospace text-muted">/api/v1/auth/login</span>', '<span class="badge bg-success">POST</span>', '120ms', '0.01%', '10 min ago'],
            ['<span class="font-monospace text-muted">/api/v1/orders</span>', '<span class="badge bg-primary">GET</span>', '250ms', '0.5%', '15 min ago'],
            ['<span class="font-monospace text-muted">/api/v1/checkout</span>', '<span class="badge bg-success">POST</span>', '400ms', '1.2%', '1 hour ago'],
        ]
    ],
    'Settings' => [
        'icon' => 'fa-gear',
        'title' => 'Plugin Settings',
        'btn' => 'Save Changes',
        'headers' => ['Configuration Key', 'Description', 'Value', 'Type', 'Actions'],
        'rows' => [
            ['max_concurrent_users', 'Max virtual users allowed per test', '5000', 'Integer'],
            ['default_timeout', 'Default HTTP timeout (seconds)', '30', 'Integer'],
            ['slack_webhook', 'Webhook for test failure alerts', 'https://hooks.slack.com/...', 'String'],
            ['log_retention_days', 'Days to keep historical test data', '90', 'Integer'],
        ]
    ],
    'Logs' => [
        'icon' => 'fa-terminal',
        'title' => 'Execution Logs',
        'btn' => 'Clear Logs',
        'headers' => ['Timestamp', 'Level', 'Component', 'Message', 'Actions'],
        'rows' => [
            ['2026-07-21 15:30:00', '<span class="badge bg-info">INFO</span>', 'Worker', 'Started load test [ID: 45]'],
            ['2026-07-21 15:30:05', '<span class="badge bg-warning text-dark">WARN</span>', 'Engine', 'High memory usage detected (85%)'],
            ['2026-07-21 15:35:12', '<span class="badge bg-success">INFO</span>', 'Worker', 'Completed load test [ID: 45] successfully'],
        ]
    ],
];

foreach ($views as $folder => $data) {
    $path = __DIR__ . "/../plugins/LoadTesting/templates/{$folder}/index.php";
    
    $html = '<div class="row g-4 m-2">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-white fw-bold m-0"><i class="fa-solid ' . $data['icon'] . ' text-primary me-2"></i> ' . $data['title'] . '</h4>
            <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold"><i class="fa-solid fa-plus me-1"></i> ' . $data['btn'] . '</a>
        </div>
        
        <div class="glass-card">
            <table class="table table-custom w-100 mb-0">
                <thead>
                    <tr>';
    
    foreach ($data['headers'] as $h) {
        $html .= '<th>' . $h . '</th>';
    }
    
    $html .= '</tr>
                </thead>
                <tbody>';
                
    foreach ($data['rows'] as $r) {
        $html .= '<tr>';
        foreach ($r as $i => $cell) {
            $html .= '<td' . ($i === 0 && strpos($cell, '<span') === false ? ' class="fw-bold"' : '') . '>' . $cell . '</td>';
        }
        $html .= '<td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr>';
    }
    
    $html .= '</tbody>
            </table>
        </div>
    </div>
</div>';

    file_put_contents($path, $html);
}
echo "Generated all views!";
