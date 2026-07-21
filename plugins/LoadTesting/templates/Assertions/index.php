<div class="row g-4 m-2">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-white fw-bold m-0"><i class="fa-solid fa-check-double text-primary me-2"></i> Global Assertions</h4>
            <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold"><i class="fa-solid fa-plus me-1"></i> New Assertion</a>
        </div>
        
        <div class="glass-card">
            <table class="table table-custom w-100 mb-0">
                <thead>
                    <tr><th>Assertion Name</th><th>Target</th><th>Condition</th><th>Value</th><th>Severity</th><th>Actions</th></tr>
                </thead>
                <tbody><tr><td class="fw-bold">Fast Response</td><td>Response Time</td><td>&lt;</td><td>500ms</td><td><span class="badge bg-danger">Critical</span></td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr><tr><td class="fw-bold">No Server Errors</td><td>Status Code</td><td>!=</td><td>5xx</td><td><span class="badge bg-danger">Critical</span></td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr><tr><td class="fw-bold">Contains API Version</td><td>JSON Body</td><td>hasKey</td><td>"version"</td><td><span class="badge bg-warning text-dark">Warning</span></td><td>
                    <a href="#" class="text-primary me-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="text-success me-3" title="View/Run"><i class="fa-solid fa-play"></i></a>
                    <a href="#" class="text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                  </td>
                </tr></tbody>
            </table>
        </div>
    </div>
</div>