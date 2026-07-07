<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Brand $brand
 */
$this->assign('title', 'Brand - ' . h($brand->name));
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-solid fa-copyright text-primary me-2"></i><?= h($brand->name) ?>
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/') ?>" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Brands</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $brand->id]) ?>" class="btn btn-warning shadow-sm fw-bold">
            <i class="fa-solid fa-pen-to-square me-2"></i>Edit
        </a>
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-2"></i>Delete',
            ['action' => 'delete', $brand->id],
            [
                'confirm' => __('Are you sure you want to delete # {0}?', $brand->id),
                'class' => 'btn btn-outline-danger shadow-sm fw-bold',
                'escape' => false
            ]
        ) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Brand Info Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center p-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                    <i class="fa-solid fa-tags fs-1 text-primary"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1"><?= h($brand->name) ?></h4>
                <p class="text-muted mb-4"><i class="fa-solid fa-link me-2 small"></i><?= h($brand->slug) ?></p>
                
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Status</span>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Active</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">ID Number</span>
                        <span class="text-dark fw-medium">#<?= $brand->id ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Created On</span>
                        <span class="text-dark fw-medium"><?= h($brand->created->format('M d, Y')) ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Related Products Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-box-open text-primary me-2"></i>Products by <?= h($brand->name) ?></h6>
                <span class="badge bg-secondary rounded-pill px-3 py-2"><?= !empty($brand->products) ? count($brand->products) : 0 ?> Products</span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($brand->products)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="px-4 py-3">Product Name</th>
                                <th class="py-3">SKU</th>
                                <th class="py-3 text-end">Price</th>
                                <th class="px-4 py-3 text-center">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($brand->products as $product): ?>
                            <tr>
                                <td class="px-4 fw-bold text-dark">
                                    <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'view', $product->id]) ?>" class="text-decoration-none">
                                        <?= h($product->name) ?>
                                    </a>
                                </td>
                                <td class="font-monospace text-muted small"><?= h($product->sku) ?></td>
                                <td class="text-end fw-medium"><?= $this->Number->currency($product->price, 'USD') ?></td>
                                <td class="px-4 text-center">
                                    <?php if ($product->stock > 0): ?>
                                        <span class="badge bg-success"><?= $this->Number->format($product->stock) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="p-5 text-center text-muted">
                    <i class="fa-solid fa-box-open fs-1 mb-3 d-block text-light"></i>
                    <p class="mb-0">No products are currently assigned to this brand.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>