<?php
declare(strict_types=1);

namespace App\Controller;

use App\Event\DomainEvents;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
 * Phase 10 — EventDemosController
 *
 * Demonstrates ALL controller-level events and provides test actions
 * to manually fire each of the 5 custom domain events.
 *
 * CONTROLLER EVENT LIFECYCLE:
 *   1. Controller.initialize  → runs once when controller is constructed
 *   2. Controller.startup     → runs before every action (same as beforeFilter)
 *   3. Controller.beforeFilter → runs before the action method
 *   4. [ACTION METHOD]        → your actual action code runs here
 *   5. Controller.beforeRender → runs after action, before view renders
 *   6. Controller.afterFilter → runs after response is prepared
 *   7. Controller.shutdown    → runs after response is sent
 *
 * URL: /event-demos/...
 */
class EventDemosController extends AppController
{
    // =========================================================================
    // CONTROLLER EVENT: initialize()
    // =========================================================================
    /**
     * Controller.initialize — Runs ONCE when the controller object is created.
     *
     * Use for: loading components, setting defaults.
     * This is NOT an event callback — it's a hook method. But it corresponds
     * to the Controller.initialize event internally.
     */
    public function initialize(): void
    {
        parent::initialize();

        Log::info('[PHASE 10] Controller.initialize — EventDemosController loaded');
    }

    // =========================================================================
    // CONTROLLER EVENT: beforeFilter()
    // =========================================================================
    /**
     * Controller.beforeFilter — Runs BEFORE every action in this controller.
     *
     * Use for: authentication checks, permission gates, request validation.
     * Returning a Response object from here will short-circuit the action.
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        // Skip auth for demo purposes
        $this->Authorization->skipAuthorization();
        $this->Authentication->addUnauthenticatedActions([
            'index', 'testUserRegistered', 'testOrderCreated',
            'testInvoicePaid', 'testEmailSent', 'testProductLowStock',
        ]);

        Log::info(sprintf(
            '[PHASE 10] Controller.beforeFilter — Action: %s, Method: %s',
            $this->request->getParam('action'),
            $this->request->getMethod()
        ));
    }

    // =========================================================================
    // CONTROLLER EVENT: beforeRender()
    // =========================================================================
    /**
     * Controller.beforeRender — Runs AFTER the action, BEFORE the view renders.
     *
     * Use for: setting global view variables, choosing layout, adding helpers.
     */
    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);

        // Share a global variable with all views rendered by this controller
        $this->set('phase10Info', 'Phase 10 — Events System Demo');

        Log::info(sprintf(
            '[PHASE 10] Controller.beforeRender — Template: %s',
            $this->viewBuilder()->getTemplate() ?: 'default'
        ));
    }

    // =========================================================================
    // CONTROLLER EVENT: afterFilter() / shutdown()
    // =========================================================================
    /**
     * Controller.shutdown — Runs AFTER the response has been sent.
     *
     * Use for: cleanup tasks, logging, queue jobs that don't affect the response.
     */
    public function afterFilter(EventInterface $event): void
    {
        parent::afterFilter($event);

        Log::info('[PHASE 10] Controller.shutdown/afterFilter — Response sent, cleanup running');
    }

    // =========================================================================
    // INDEX — Dashboard showing all events
    // =========================================================================
    public function index()
    {
        // Show event demo page
        $events = [
            ['name' => 'Controller.initialize', 'type' => 'Core', 'description' => 'Fires when controller is created'],
            ['name' => 'Controller.beforeFilter', 'type' => 'Core', 'description' => 'Fires before every action'],
            ['name' => 'Controller.beforeRender', 'type' => 'Core', 'description' => 'Fires after action, before view'],
            ['name' => 'Controller.shutdown', 'type' => 'Core', 'description' => 'Fires after response sent'],
            ['name' => DomainEvents::USER_REGISTERED, 'type' => 'Custom', 'description' => 'User account created'],
            ['name' => DomainEvents::ORDER_CREATED, 'type' => 'Custom', 'description' => 'New order placed'],
            ['name' => DomainEvents::INVOICE_PAID, 'type' => 'Custom', 'description' => 'Invoice marked as paid'],
            ['name' => DomainEvents::EMAIL_SENT, 'type' => 'Custom', 'description' => 'Email sent and logged'],
            ['name' => DomainEvents::PRODUCT_LOW_STOCK, 'type' => 'Custom', 'description' => 'Product stock below threshold'],
        ];
        $this->set(compact('events'));

        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['events']);
    }

    // =========================================================================
    // TEST: Fire User.registered event
    // =========================================================================
    public function testUserRegistered()
    {
        // Create a mock user entity to simulate registration
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $mockUser = $usersTable->newEmptyEntity();
        $mockUser->id = 999;
        $mockUser->username = 'test_user_' . time();
        $mockUser->email = 'test_' . time() . '@example.com';

        // Dispatch the custom event
        EventManager::instance()->dispatch(new Event(
            DomainEvents::USER_REGISTERED,
            $this,
            ['user' => $mockUser]
        ));

        $message = 'User.registered event dispatched for: ' . $mockUser->username;
        $this->set(compact('message'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['message']);
    }

    // =========================================================================
    // TEST: Fire Order.created event
    // =========================================================================
    public function testOrderCreated()
    {
        $ordersTable = TableRegistry::getTableLocator()->get('Orders');
        $mockOrder = $ordersTable->newEmptyEntity();
        $mockOrder->id = 999;
        $mockOrder->order_number = 'ORD-TEST-' . time();
        $mockOrder->total = 250.00;
        $mockOrder->user_id = 1;

        EventManager::instance()->dispatch(new Event(
            DomainEvents::ORDER_CREATED,
            $this,
            ['order' => $mockOrder]
        ));

        $message = 'Order.created event dispatched for: ' . $mockOrder->order_number;
        $this->set(compact('message'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['message']);
    }

    // =========================================================================
    // TEST: Fire Invoice.paid event
    // =========================================================================
    public function testInvoicePaid()
    {
        $invoicesTable = TableRegistry::getTableLocator()->get('Invoices');
        $mockInvoice = $invoicesTable->newEmptyEntity();
        $mockInvoice->id = 999;
        $mockInvoice->invoice_number = 'INV-TEST-' . time();
        $mockInvoice->amount = 500.00;
        $mockInvoice->order_id = 1;

        EventManager::instance()->dispatch(new Event(
            DomainEvents::INVOICE_PAID,
            $this,
            ['invoice' => $mockInvoice]
        ));

        $message = 'Invoice.paid event dispatched for: ' . $mockInvoice->invoice_number;
        $this->set(compact('message'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['message']);
    }

    // =========================================================================
    // TEST: Fire Email.sent event
    // =========================================================================
    public function testEmailSent()
    {
        $sentEmailsTable = TableRegistry::getTableLocator()->get('SentEmails');
        $mockEmail = $sentEmailsTable->newEmptyEntity();
        $mockEmail->id = 999;
        $mockEmail->recipient_email = 'customer@example.com';
        $mockEmail->subject = 'Test Email — Phase 10 Demo';

        EventManager::instance()->dispatch(new Event(
            DomainEvents::EMAIL_SENT,
            $this,
            ['email' => $mockEmail]
        ));

        $message = 'Email.sent event dispatched to: ' . $mockEmail->recipient_email;
        $this->set(compact('message'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['message']);
    }

    // =========================================================================
    // TEST: Fire Product.lowStock event
    // =========================================================================
    public function testProductLowStock()
    {
        $productsTable = TableRegistry::getTableLocator()->get('Products');
        $mockProduct = $productsTable->newEmptyEntity();
        $mockProduct->id = 999;
        $mockProduct->name = 'Test Widget';
        $mockProduct->sku = 'TST-001';
        $mockProduct->stock = 3;

        EventManager::instance()->dispatch(new Event(
            DomainEvents::PRODUCT_LOW_STOCK,
            $this,
            ['product' => $mockProduct, 'threshold' => 5]
        ));

        $message = 'Product.lowStock event dispatched for: ' . $mockProduct->name . ' (stock: ' . $mockProduct->stock . ')';
        $this->set(compact('message'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['message']);
    }
}
