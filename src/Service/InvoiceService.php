<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Invoice;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Exception;

/**
 * Invoice Service
 *
 * Handles automatic invoice generation from orders, invoice number sequencing,
 * and ledger integration as per the ERP workflow rules.
 */
class InvoiceService
{
    use LocatorAwareTrait;

    /**
     * Automatically generates an Invoice from a Completed or Accepted Order.
     *
     * @param int $orderId The ID of the order to invoice
     * @param string|null $notes Optional invoice notes
     * @return \App\Model\Entity\Invoice
     * @throws \Exception
     */
    public function generateFromOrder(int $orderId, ?string $notes = null): Invoice
    {
        $ordersTable = $this->fetchTable('Orders');
        $invoicesTable = $this->fetchTable('Invoices');

        $order = $ordersTable->get($orderId, [
            'contain' => ['OrderItems'],
        ]);

        // Business Rule: Generate invoice only if Order is in a valid state
        $allowedStatuses = ['Pending', 'Confirmed', 'Accepted', 'Processing', 'Completed'];
        if (!in_array($order->status, $allowedStatuses)) {
            throw new Exception("Invoices can only be generated for orders past Draft status. Current status: {$order->status}");
        }

        // Check if invoice already exists to prevent duplicates
        $existing = $invoicesTable->find()->where(['order_id' => $orderId])->first();
        if ($existing) {
            throw new Exception('Invoice already exists for this order.');
        }

        $connection = ConnectionManager::get('default');

        return $connection->transactional(function () use ($order, $invoicesTable, $notes) {

            $invoice = $invoicesTable->newEmptyEntity();
            $invoice->order_id = $order->id;

            // Auto-generate invoice number: INV-YYYY-000001
            $year = date('Y');
            // Safely get the max id to generate sequence
            $lastInvoice = $invoicesTable->find()
                ->select(['id'])
                ->orderBy(['id' => 'DESC'])
                ->first();
            $nextSequence = $lastInvoice ? $lastInvoice->id + 1 : 1;
            $invoice->invoice_number = sprintf('INV-%s-%06d', $year, $nextSequence);

            $invoice->amount = $order->total;
            $invoice->tax = $order->tax;
            $invoice->discount = $order->discount;
            // The grand total on the invoice is implied by amount + tax - discount

            $invoice->status = 'Generated';
            $invoice->due_date = (new FrozenTime())->addDays(14); // Default Net 14
            $invoice->notes = $notes ?? 'Auto-generated invoice from POS order.';

            $savedInvoice = $invoicesTable->saveOrFail($invoice);

            // Generate ledger entries (To be implemented in Phase 3 / Ledger Module)

            return $savedInvoice;
        });
    }
}
