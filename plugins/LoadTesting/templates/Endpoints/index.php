<div class="row g-4 m-2">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-white fw-bold m-0"><i class="fa-solid fa-network-wired text-primary me-2"></i> Monitored Endpoints</h4>
            <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold"><i class="fa-solid fa-plus me-1"></i> Add Endpoint</a>
        </div>
        
        <div class="glass-card">
            <table class="table table-custom w-100 mb-0">
                <thead>
                    <tr><th>Path</th><th>Method</th><th>Avg Latency</th><th>Error Rate</th><th>Last Tested</th><th>Actions</th></tr>
                </thead>
                <tbody><tr><td><span class="font-monospace text-muted">/api/v1/auth/login</span></td><td><span class="badge bg-success">POST</span></td><td>120ms</td><td>0.01%</td><td>10 min ago</td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr><tr><td><span class="font-monospace text-muted">/api/v1/orders</span></td><td><span class="badge bg-primary">GET</span></td><td>250ms</td><td>0.5%</td><td>15 min ago</td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr><tr><td><span class="font-monospace text-muted">/api/v1/checkout</span></td><td><span class="badge bg-success">POST</span></td><td>400ms</td><td>1.2%</td><td>1 hour ago</td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr></tbody>
            </table>
        </div>
    </div>
</div>