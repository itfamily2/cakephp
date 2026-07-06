<?php
declare(strict_types=1);

namespace App\Event\Listener;

use App\Event\DomainEvents;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
 * Phase 10 — NotificationListener
 *
 * Centralized handler for all 5 custom domain events.
 * Registered in Application::bootstrap() via EventManager::instance()->on(new NotificationListener()).
 */
class NotificationListener implements EventListenerInterface
{
    public function implementedEvents(): array
    {
        return [
            DomainEvents::USER_REGISTERED   => 'onUserRegistered',
            DomainEvents::ORDER_CREATED     => 'onOrderCreated',
            DomainEvents::INVOICE_PAID      => 'onInvoicePaid',
            DomainEvents::EMAIL_SENT        => 'onEmailSent',
            DomainEvents::PRODUCT_LOW_STOCK => 'onProductLowStock',
        ];
    }

    /**
     * User.registered — log registration, record activity.
     */
    public function onUserRegistered(EventInterface $event): void
    {
        $user = $event->getData('user');
        Log::info(sprintf(
            '[EVENT] User.registered — ID: %d, Username: %s, Email: %s',
            $user->id ?? 0, $user->username ?? 'unknown', $user->email ?? 'unknown'
        ));

        try {
            $table = TableRegistry::getTableLocator()->get('ActivityLogs');
            $log = $table->newEmptyEntity();
            $log->user_id = $user->id ?? null;
            $log->action = 'User.registered';
            $log->description = sprintf('New user registered: %s (%s)', $user->username ?? 'unknown', $user->email ?? 'unknown');
            $log->ip_address = '127.0.0.1';
            $table->save($log);
        } catch (\Exception $e) {
            Log::warning('[EVENT] Failed to log User.registered: ' . $e->getMessage());
        }
    }

    /**
     * Order.created — log order, auto-create invoice.
     */
    public function onOrderCreated(EventInterface $event): void
    {
        $order = $event->getData('order');
        Log::info(sprintf(
            '[EVENT] Order.created — ID: %d, Order#: %s, Total: %s',
            $order->id ?? 0, $order->order_number ?? 'N/A', $order->total ?? '0.00'
        ));

        try {
            $invoicesTable = TableRegistry::getTableLocator()->get('Invoices');
            $invoice = $invoicesTable->newEmptyEntity();
            $invoice->order_id = $order->id;
            $invoice->invoice_number = 'INV-' . str_pad((string)($order->id ?? 0), 6, '0', STR_PAD_LEFT);
            $invoice->amount = $order->total ?? 0;
            $invoice->tax = 0;
            $invoice->discount = 0;
            $invoice->status = 'unpaid';
            $invoice->due_date = new \Cake\I18n\DateTime('+30 days');
            $invoicesTable->save($invoice);
        } catch (\Exception $e) {
            Log::warning('[EVENT] Failed to auto-create invoice: ' . $e->getMessage());
        }
    }

    /**
     * Invoice.paid — update parent order status to Paid.
     */
    public function onInvoicePaid(EventInterface $event): void
    {
        $invoice = $event->getData('invoice');
        Log::info(sprintf(
            '[EVENT] Invoice.paid — ID: %d, Invoice#: %s, Amount: %s',
            $invoice->id ?? 0, $invoice->invoice_number ?? 'N/A', $invoice->amount ?? '0.00'
        ));

        try {
            if (!empty($invoice->order_id)) {
                $ordersTable = TableRegistry::getTableLocator()->get('Orders');
                $order = $ordersTable->get($invoice->order_id);
                $order->status = 'Paid';
                $ordersTable->save($order);
            }
        } catch (\Exception $e) {
            Log::warning('[EVENT] Failed to update order after Invoice.paid: ' . $e->getMessage());
        }
    }

    /**
     * Email.sent — log for audit trail.
     */
    public function onEmailSent(EventInterface $event): void
    {
        $email = $event->getData('email');
        Log::info(sprintf(
            '[EVENT] Email.sent — To: %s, Subject: %s',
            $email->recipient_email ?? 'unknown', $email->subject ?? 'N/A'
        ));

        try {
            $table = TableRegistry::getTableLocator()->get('ActivityLogs');
            $log = $table->newEmptyEntity();
            $log->user_id = null;
            $log->action = 'Email.sent';
            $log->description = sprintf('Email sent to %s: "%s"', $email->recipient_email ?? 'unknown', $email->subject ?? 'N/A');
            $log->ip_address = '127.0.0.1';
            $table->save($log);
        } catch (\Exception $e) {
            Log::warning('[EVENT] Failed to log Email.sent: ' . $e->getMessage());
        }
    }

    /**
     * Product.lowStock — alert procurement team.
     */
    public function onProductLowStock(EventInterface $event): void
    {
        $product = $event->getData('product');
        $threshold = $event->getData('threshold') ?? 5;
        Log::warning(sprintf(
            '[EVENT] Product.lowStock — ID: %d, Name: %s, SKU: %s, Stock: %d (threshold: %d)',
            $product->id ?? 0, $product->name ?? 'unknown', $product->sku ?? 'N/A',
            $product->stock ?? 0, $threshold
        ));

        try {
            $table = TableRegistry::getTableLocator()->get('ActivityLogs');
            $log = $table->newEmptyEntity();
            $log->user_id = null;
            $log->action = 'Product.lowStock';
            $log->description = sprintf(
                'LOW STOCK: %s (SKU: %s) — %d units remaining (threshold: %d)',
                $product->name ?? 'unknown', $product->sku ?? 'N/A',
                $product->stock ?? 0, $threshold
            );
            $log->ip_address = '127.0.0.1';
            $table->save($log);
        } catch (\Exception $e) {
            Log::warning('[EVENT] Failed to log Product.lowStock: ' . $e->getMessage());
        }
    }
}
