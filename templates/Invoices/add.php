<?php $this->assign('title', 'Generate Invoice'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-file-invoice text-primary me-2"></i>Generate Invoice</h4>
        <p class="text-muted mb-0">Select an order to automatically generate a billing invoice.</p>
    </div>
    <div>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary bg-white shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Invoices
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-wand-magic-sparkles text-warning me-2"></i>Invoice Generator</h6>
            </div>
            <div class="card-body p-5">
                <?= $this->Form->create($invoice, ['id' => 'invoice-form', 'class' => 'needs-validation']) ?>
                
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-file-invoice-dollar fs-1 text-primary"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Link to Order</h5>
                    <p class="text-muted small">You cannot generate an invoice without an underlying order. Please select an eligible order from the list below. All amounts, taxes, and line items will be pulled automatically.</p>
                </div>

                <div class="mb-4">
                    <?= $this->Form->control('order_id', [
                        'options' => $orders,
                        'empty' => '-- Select an Order to Invoice --',
                        'default' => $this->request->getQuery('order_id'),
                        'class' => 'form-select form-select-lg text-dark shadow-sm',
                        'label' => ['class' => 'form-label fw-bold text-muted small'],
                        'required' => true
                    ]) ?>
                </div>
                
                <div class="mb-4">
                    <?= $this->Form->control('status', [
                        'options' => ['Draft' => 'Draft', 'Generated' => 'Generated', 'Sent' => 'Sent', 'Paid' => 'Paid'],
                        'default' => 'Generated',
                        'class' => 'form-select text-dark shadow-sm',
                        'label' => ['class' => 'form-label fw-bold text-muted small']
                    ]) ?>
                </div>

                <div class="mb-4">
                    <?= $this->Form->control('due_date', [
                        'type' => 'date',
                        'class' => 'form-control text-dark shadow-sm',
                        'label' => ['class' => 'form-label fw-bold text-muted small'],
                        'default' => date('Y-m-d', strtotime('+14 days'))
                    ]) ?>
                </div>
                
                <div class="mb-5">
                    <?= $this->Form->control('notes', [
                        'type' => 'textarea',
                        'rows' => 3,
                        'class' => 'form-control text-dark shadow-sm',
                        'placeholder' => 'Thank you for your business...',
                        'label' => ['class' => 'form-label fw-bold text-muted small']
                    ]) ?>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" id="generate-btn" class="btn btn-primary btn-lg flex-grow-1 shadow-sm fw-bold">
                        <i class="fa-solid fa-bolt me-2"></i>Generate Invoice
                    </button>
                </div>
                
                <?= $this->Form->end() ?>
            </div>
            <div class="card-footer bg-light border-top text-center py-3">
                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> PDF Generation requires CakePdf & Dompdf to be installed.</small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('invoice-form');
    const btn = document.getElementById('generate-btn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Generating...';

        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Invoice Generated!',
                    text: data.message,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa-solid fa-file-pdf me-2"></i>Download PDF',
                    cancelButtonText: 'Back to Ledger',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= $this->Url->build(['action' => 'view']) ?>/' + data.invoice.id + '.pdf';
                    } else {
                        window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                    }
                });
            } else {
                Swal.fire('Error', data.message || 'Generation failed.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-bolt me-2"></i>Generate Invoice';
            }
        })
        .catch(err => {
            Swal.fire('Error', 'Unexpected server error.', 'error');
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-bolt me-2"></i>Generate Invoice';
        });
    });
});
</script>
