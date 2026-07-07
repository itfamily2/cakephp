<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice <?= h($invoice->invoice_number) ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: right; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 28px; color: #000; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #666; }
        .row { width: 100%; display: block; margin-bottom: 30px; }
        .col-left { float: left; width: 50%; }
        .col-right { float: right; width: 50%; text-align: right; }
        .clear { clear: both; }
        .details h4 { margin-bottom: 5px; font-size: 14px; color: #666; text-transform: uppercase; }
        .details p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f8f9fa; text-align: left; padding: 12px; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #ddd; }
        td { padding: 12px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right !important; }
        .totals { width: 40%; float: right; }
        .totals-row { padding: 8px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; }
        .totals-row:last-child { border-bottom: none; font-weight: bold; font-size: 18px; border-top: 2px solid #333; padding-top: 10px; margin-top: 5px; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <h1>INVOICE</h1>
        <h2><?= h($invoice->invoice_number) ?></h2>
        <p><strong>YEELO ERP</strong><br>123 Tech Lane, Suite 400<br>San Francisco, CA 94107<br>contact@yeelo.com</p>
    </div>

    <div class="row">
        <div class="col-left details">
            <h4>Billed To</h4>
            <?php if ($invoice->hasValue('order') && $invoice->order->hasValue('user')): ?>
                <p><strong><?= h($invoice->order->user->username) ?></strong></p>
                <p><?= h($invoice->order->user->email) ?></p>
            <?php else: ?>
                <p><strong>Guest Customer</strong></p>
            <?php endif; ?>
        </div>
        <div class="col-right details">
            <h4>Invoice Details</h4>
            <p><strong>Issued:</strong> <?= h($invoice->created->format('M d, Y')) ?></p>
            <p><strong>Due Date:</strong> <?= $invoice->due_date ? h($invoice->due_date->format('M d, Y')) : 'Upon Receipt' ?></p>
            <p><strong>Order Ref:</strong> <?= $invoice->hasValue('order') ? h($invoice->order->order_number) : '#' . $invoice->order_id ?></p>
        </div>
        <div class="clear"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($invoice->hasValue('order') && !empty($invoice->order->order_items)): ?>
                <?php foreach ($invoice->order->order_items as $item): ?>
                <tr>
                    <td>
                        <strong><?= $item->hasValue('product') ? h($item->product->name) : 'Product #' . $item->product_id ?></strong><br>
                        <?php if ($item->discount > 0): ?>
                        <span style="font-size: 12px; color: #dc3545;">Includes <?= $this->Number->currency($item->discount, 'USD') ?> discount</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?= $this->Number->format($item->quantity) ?></td>
                    <td class="text-right"><?= $this->Number->currency($item->price, 'USD') ?></td>
                    <td class="text-right"><?= $this->Number->currency($item->line_total, 'USD') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">No line items found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="totals">
        <table style="border: none; margin: 0;">
            <tr>
                <td style="border: none; padding: 5px 0;">Subtotal</td>
                <td class="text-right" style="border: none; padding: 5px 0;"><?= $this->Number->currency($invoice->amount, 'USD') ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0;">Tax</td>
                <td class="text-right" style="border: none; padding: 5px 0;"><?= $this->Number->currency($invoice->tax, 'USD') ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0; color: #dc3545;">Discount</td>
                <td class="text-right" style="border: none; padding: 5px 0; color: #dc3545;">-<?= $this->Number->currency($invoice->discount, 'USD') ?></td>
            </tr>
            <tr>
                <td style="border-top: 2px solid #333; padding: 10px 0; font-size: 16px; font-weight: bold;">Total Due</td>
                <td class="text-right" style="border-top: 2px solid #333; padding: 10px 0; font-size: 16px; font-weight: bold;"><?= $this->Number->currency($invoice->amount + $invoice->tax - $invoice->discount, 'USD') ?></td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>

    <?php if (!empty($invoice->notes)): ?>
        <div class="footer">
            <h4>Notes</h4>
            <p><?= nl2br(h($invoice->notes)) ?></p>
        </div>
    <?php endif; ?>

</body>
</html>
