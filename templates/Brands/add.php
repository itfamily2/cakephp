<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Brand $brand
 */
$this->assign('title', 'Add New Brand');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-solid fa-copyright text-primary me-2"></i>New Brand
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build('/') ?>" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Brands</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-layer-group text-muted me-2"></i>Brand Details</h6>
            </div>
            <div class="card-body p-5">
                <?= $this->Form->create($brand, ['class' => 'needs-validation']) ?>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <?= $this->Form->control('name', [
                            'class' => 'form-control form-control-lg shadow-sm',
                            'label' => ['class' => 'form-label fw-bold text-muted small'],
                            'placeholder' => 'e.g. Dr. Reckeweg',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('slug', [
                            'class' => 'form-control form-control-lg shadow-sm',
                            'label' => ['class' => 'form-label fw-bold text-muted small'],
                            'placeholder' => 'dr-reckeweg (auto-generated if empty)',
                            'help' => 'Leave blank to auto-generate from name'
                        ]) ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5 border-top pt-4">
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light border shadow-sm px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary shadow-sm px-4 fw-bold">
                        <i class="fa-solid fa-save me-2"></i>Save Brand
                    </button>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
