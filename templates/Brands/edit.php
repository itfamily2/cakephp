<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Brand $brand
 */
$this->assign('title', 'Edit Brand - ' . h($brand->name));
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Brand: <?= h($brand->name) ?>
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/') ?>" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Brands</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <?= $this->Form->postLink(
            '<i class="fa-solid fa-trash me-2"></i>Delete',
            ['action' => 'delete', $brand->id],
            [
                'confirm' => __('Are you sure you want to delete # {0}?', $brand->id),
                'class' => 'btn btn-outline-danger shadow-sm',
                'escape' => false
            ]
        ) ?>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-sliders text-muted me-2"></i>Brand Settings</h6>
            </div>
            <div class="card-body p-5">
                <?= $this->Form->create($brand, ['class' => 'needs-validation']) ?>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <?= $this->Form->control('name', [
                            'class' => 'form-control form-control-lg shadow-sm',
                            'label' => ['class' => 'form-label fw-bold text-muted small'],
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('slug', [
                            'class' => 'form-control form-control-lg shadow-sm',
                            'label' => ['class' => 'form-label fw-bold text-muted small'],
                            'required' => true
                        ]) ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5 border-top pt-4">
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light border shadow-sm px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary shadow-sm px-4 fw-bold">
                        <i class="fa-solid fa-save me-2"></i>Update Brand
                    </button>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
