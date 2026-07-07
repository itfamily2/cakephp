<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
$this->assign('title', 'Invoice ' . h($invoice->invoice_number));
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="fa-solid fa-file-invoice text-primary me-2"></i><?= h($invoice->invoice_number) ?>
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0 mt-2">
                <li class="breadcrumb-item"><a href="<?= $this->Url->build(['action' => 'index']) ?>" class="text-decoration-none">Invoices</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $invoice->id]) ?>" class="btn btn-warning shadow-sm">
            <i class="fa-solid fa-pen-to-square me-2"></i>Edit
        </a>
        <a href="<?= $this->Url->build(['action' => 'view', $invoice->id, '_ext' => 'pdf']) ?>" class="btn btn-primary shadow-sm fw-bold">
            <i class="fa-solid fa-download me-2"></i>Download PDF
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Invoice Preview Card -->
        <div class="card shadow-sm border-0 mb-4 p-md-5 p-3">
            <div class="d-flex justify-content-between border-bottom pb-4 mb-4">
                <div>
                    <h3 class="fw-bold text-uppercase text-muted tracking-wide mb-1">INVOICE</h3>
                    <h5 class="text-dark fw-bold"><?= h($invoice->invoice_number) ?></h5>
                </div>
                <div class="text-end">
                    <h3 class="fw-bold text-primary mb-1">YEELO ERP</h3>
                    <p class="text-muted small mb-0">123 Tech Lane, Suite 400<br>San Francisco, CA 94107<br>contact@yeelo.com</p>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-sm-6">
                    <h6 class="text-muted fw-bold small text-uppercase mb-2">Billed To</h6>
                    <?php if ($invoice->hasValue('order') && $invoice->order->hasValue('user')): ?>
                        <p class="text-dark fw-medium mb-1"><?= h($invoice->order->user->username) ?></p>
                        <p class="text-muted small mb-0"><?= h($invoice->order->user->email) ?></p>
                    <?php else: ?>
                        <p class="text-dark fw-medium mb-1">Guest Customer</p>
                    <?php endif; ?>
                </div>
                <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                    <h6 class="text-muted fw-bold small text-uppercase mb-2">Invoice Details</h6>
                    <p class="text-dark small mb-1"><span class="fw-bold text-muted me-2">Issued:</span> <?= h($invoice->created->format('M d, Y')) ?></p>
                    <p class="text-dark small mb-1"><span class="fw-bold text-muted me-2">Due Date:</span> <?= $invoice->due_date ? h($invoice->due_date->format('M d, Y')) : 'Upon Receipt' ?></p>
                    <p class="text-dark small mb-0"><span class="fw-bold text-muted me-2">Order Ref:</span> <a href="<?= $this->Url->build(['controller' => 'Orders', 'action' => 'view', $invoice->order_id]) ?>"><?= $invoice->hasValue('order') ? h($invoice->order->order_number) : '#' . $invoice->order_id ?></a></p>
                </div>
            </div>

            <!-- Totals -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small text-muted fw-bold">Description</th>
                            <th class="text-uppercase small text-muted fw-bold text-center">Qty</th>
                            <th class="text-uppercase small text-muted fw-bold text-end">Price</th>
                            <th class="text-uppercase small text-muted fw-bold text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($invoice->hasValue('order') && !empty($invoice->order->order_items)): ?>
                            <?php foreach ($invoice->order->order_items as $item): ?>
                            <tr>
                                <td class="py-3">
                                    <span class="fw-bold text-dark"><?= $item->hasValue('product') ? h($item->product->name) : 'Product #' . $item->product_id ?></span>
                                    <?php if ($item->discount > 0): ?>
                                    <div class="small text-danger mt-1">Includes <?= $this->Number->currency($item->discount, 'USD') ?> discount</div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center py-3 fw-medium text-dark"><?= $this->Number->format($item->quantity) ?></td>
                                <td class="text-end py-3 fw-medium text-dark"><?= $this->Number->currency($item->price, 'USD') ?></td>
                                <td class="text-end py-3 fw-medium text-dark"><?= $this->Number->currency($item->line_total, 'USD') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No line items found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-sm-6 col-md-5">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fw-medium">Subtotal</span>
                        <span class="text-dark"><?= $this->Number->currency($invoice->amount, 'USD') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fw-medium">Tax</span>
                        <span class="text-dark"><?= $this->Number->currency($invoice->tax, 'USD') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span class="text-danger fw-medium">Discount</span>
                        <span class="text-danger">-<?= $this->Number->currency($invoice->discount, 'USD') ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-dark fw-bold text-uppercase">Total Due</span>
                        <h4 class="text-primary fw-bold mb-0"><?= $this->Number->currency($invoice->amount + $invoice->tax - $invoice->discount, 'USD') ?></h4>
                    </div>
                </div>
            </div>

            <?php if (!empty($invoice->notes)): ?>
                <div class="mt-5 pt-4 border-top">
                    <h6 class="text-muted fw-bold small text-uppercase mb-2">Notes</h6>
                    <p class="text-muted small fst-italic mb-0"><?= nl2br(h($invoice->notes)) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar Status -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <?php
                        $badgeClass = 'bg-secondary';
                        if ($invoice->status === 'Paid') $badgeClass = 'bg-success';
                        elseif ($invoice->status === 'Generated') $badgeClass = 'bg-primary';
                        elseif ($invoice->status === 'Cancelled') $badgeClass = 'bg-danger';
                        elseif ($invoice->status === 'Draft') $badgeClass = 'bg-warning text-dark';
                    ?>
                    <span class="badge <?= $badgeClass ?> rounded-pill px-4 py-2 fs-5">
                        <?= h($invoice->status) ?>
                    </span>
                </div>
                <p class="text-muted small mb-0">Last updated <?= h($invoice->modified->timeAgoInWords()) ?></p>
            </div>
        </div>
        
        <!-- Payment Module Hook (Phase 3) -->
        <div class="card shadow-sm border-0 border-top border-4 border-success">
            <div class="card-body p-4 text-center">
                <i class="fa-solid fa-credit-card fs-1 text-success mb-3"></i>
                <h6 class="fw-bold text-dark">Payment Gateway</h6>
                <p class="text-muted small mb-3">The payment ledger module will be integrated here.</p>
                <button class="btn btn-outline-success w-100 shadow-sm" disabled>Record Payment</button>
            </div>
        </div>
    </div>
</div>