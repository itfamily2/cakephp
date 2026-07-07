<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 * @var string[]|\Cake\Collection\CollectionInterface $orders
 */
$this->assign('title', 'Edit Invoice ' . h($invoice->invoice_number));
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Invoice <?= h($invoice->invoice_number) ?>
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Invoices</a></li>
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'view', $invoice->id]) ?>" class="text-decoration-none">View</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-sliders text-muted me-2"></i>Invoice Settings</h6>
            </div>
            <div class="card-body p-5">
                <?php
                // Disable editing if the invoice is Paid or Cancelled
                $isLocked = in_array($invoice->status, ['Paid', 'Cancelled']);
                ?>
                
                <?php if ($isLocked): ?>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fa-solid fa-lock fs-4 me-3"></i>
                        <div>
                            <strong>Invoice Locked</strong><br>
                            This invoice is in a <em><?= h($invoice->status) ?></em> state and cannot be modified.
                        </div>
                    </div>
                <?php endif; ?>

                <?= $this->Form->create($invoice, ['class' => 'needs-validation']) ?>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <?= $this->Form->control('status', [
                            'options' => [
                                'Draft' => 'Draft',
                                'Generated' => 'Generated',
                                'Sent' => 'Sent',
                                'Paid' => 'Paid',
                                'Partially Paid' => 'Partially Paid',
                                'Cancelled' => 'Cancelled'
                            ],
                            'class' => 'form-select form-select-lg shadow-sm',
                            'label' => ['class' => 'form-label fw-bold text-muted small'],
                            'disabled' => $isLocked
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('due_date', [
                            'type' => 'date',
                            'class' => 'form-control form-control-lg shadow-sm',
                            'label' => ['class' => 'form-label fw-bold text-muted small'],
                            'disabled' => $isLocked
                        ]) ?>
                    </div>
                </div>

                <div class="mb-5">
                    <?= $this->Form->control('notes', [
                        'type' => 'textarea',
                        'rows' => 4,
                        'class' => 'form-control shadow-sm',
                        'placeholder' => 'Add operational notes, payment terms, or cancellation reasons...',
                        'label' => ['class' => 'form-label fw-bold text-muted small'],
                        'disabled' => $isLocked
                    ]) ?>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="<?= $this->Url->build(['action' => 'view', $invoice->id]) ?>" class="btn btn-light border shadow-sm px-4">Cancel</a>
                    <?php if (!$isLocked): ?>
                        <button type="submit" class="btn btn-primary shadow-sm px-4 fw-bold">
                            <i class="fa-solid fa-save me-2"></i>Save Changes
                        </button>
                    <?php endif; ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
