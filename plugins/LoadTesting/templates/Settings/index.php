<div class="row g-4 m-2">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-white fw-bold m-0"><i class="fa-solid fa-gear text-primary me-2"></i> Plugin Settings</h4>
            <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold"><i class="fa-solid fa-plus me-1"></i> Save Changes</a>
        </div>
        
        <div class="glass-card">
            <table class="table table-custom w-100 mb-0">
                <thead>
                    <tr><th>Configuration Key</th><th>Description</th><th>Value</th><th>Type</th><th>Actions</th></tr>
                </thead>
                <tbody><tr><td class="fw-bold">max_concurrent_users</td><td>Max virtual users allowed per test</td><td>5000</td><td>Integer</td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr><tr><td class="fw-bold">default_timeout</td><td>Default HTTP timeout (seconds)</td><td>30</td><td>Integer</td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr><tr><td class="fw-bold">slack_webhook</td><td>Webhook for test failure alerts</td><td>https://hooks.slack.com/...</td><td>String</td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr><tr><td class="fw-bold">log_retention_days</td><td>Days to keep historical test data</td><td>90</td><td>Integer</td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr></tbody>
            </table>
        </div>
    </div>
</div>