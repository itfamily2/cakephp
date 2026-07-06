<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\DateTime;

/**
 * ReportsController — Phase 4: Reports with Download, JSON, XML, and AJAX
 *
 * WHAT IT DOES:
 *   Generates aggregated business reports across Orders, Users, Products,
 *   and Invoices. Demonstrates responding in multiple formats: HTML, PDF
 *   (via CSV proxy), JSON, and XML — all from the same controller.
 *
 * PHASE 4 TECHNIQUES HERE:
 *   ✅ beforeFilter()  → date range validation applied to all report actions
 *   ✅ beforeRender()  → injects report metadata (generated_at, user) to all views
 *   ✅ JSON response   → /reports/summary.json
 *   ✅ XML response    → /reports/summary.xml
 *   ✅ Download        → /reports/export-csv downloads a file
 *   ✅ redirect()      → validation failure returns to form
 *   ✅ Flash           → error when invalid date range submitted
 *   ✅ Request         → getQuery() for date range filters
 *   ✅ AJAX            → chart data refreshes without page reload
 */
class ReportsController extends AppController
{
    /**
     * initialize() — set report-specific defaults
     */
    public function initialize(): void
    {
        parent::initialize();

        // Reports never paginate — they always return full datasets
        // so no $this->paginate defaults needed here
    }

    /**
     * beforeFilter() — Validate date range parameters for all report actions
     */
    public function beforeFilter(\Cake\Event\EventInterface $event): ?\Cake\Http\Response
    {
        parent::beforeFilter($event);

        // Parse date range from URL query params — applied to all report actions
        $from = $this->request->getQuery('from');
        $to   = $this->request->getQuery('to');

        if ($from && $to && strtotime($from) > strtotime($to)) {
            $this->Flash->error(__('Start date cannot be after end date.'));
            // Stop the action from running by redirecting
            $event->stopPropagation();
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * beforeRender() — Inject metadata into all report views
     */
    public function beforeRender(\Cake\Event\EventInterface $event): void
    {
        parent::beforeRender($event);

        // Every report template gets: when it was generated and by whom
        $identity = $this->Authentication->getIdentity();
        $this->set('reportMeta', [
            'generated_at' => new DateTime(),
            'generated_by' => $identity ? $identity->getOriginalData()->username : 'System',
        ]);
    }

    /**
     * index() — Report dashboard with summary KPIs
     * URL: GET /reports
     */
    public function index(): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        // Date range filter from query params
        $from = $this->request->getQuery('from', date('Y-m-01')); // Default: start of month
        $to   = $this->request->getQuery('to',   date('Y-m-d'));  // Default: today

        $ordersTable   = $this->fetchTable('Orders');
        $usersTable    = $this->fetchTable('Users');
        $productsTable = $this->fetchTable('Products');

        // Revenue summary
        $totalRevenue = $ordersTable->find()
            ->where(['Orders.created >=' => $from, 'Orders.created <=' => $to . ' 23:59:59'])
            ->select(['total_revenue' => $ordersTable->find()->func()->sum('Orders.total')])
            ->first()->total_revenue ?? 0;

        // Order count by status
        $ordersByStatus = $ordersTable->find()
            ->select(['status', 'count' => $ordersTable->find()->func()->count('*')])
            ->where(['Orders.created >=' => $from])
            ->groupBy('status')
            ->toArray();

        // New user registrations in range
        $newUsers = $usersTable->find()
            ->where(['Users.created >=' => $from, 'Users.created <=' => $to . ' 23:59:59'])
            ->count();

        // Top 5 products by order frequency
        $topProducts = $productsTable->find()
            ->select(['Products.id', 'Products.name',
                'order_count' => $productsTable->find()->func()->count('OrderItems.id')])
            ->leftJoinWith('OrderItems')
            ->groupBy(['Products.id', 'Products.name'])
            ->orderBy(['order_count' => 'DESC'])
            ->limit(5)
            ->toArray();

        $this->set(compact('totalRevenue', 'ordersByStatus', 'newUsers', 'topProducts', 'from', 'to'));
    }

    /**
     * summary() — Multi-format report endpoint (HTML / JSON / XML)
     *
     * HOW IT WORKS:
     *   /reports/summary      → HTML report
     *   /reports/summary.json → JSON response (route extension)
     *   /reports/summary.xml  → XML response (route extension)
     *
     * CakePHP detects the requested format from the URL extension or
     * Accept header and switches the view class automatically when you
     * call $this->viewBuilder()->setClassName().
     */
    public function summary(): void
    {
        $this->Authorization->skipAuthorization();

        $data = [
            'total_orders'   => $this->fetchTable('Orders')->find()->count(),
            'total_users'    => $this->fetchTable('Users')->find()->count(),
            'total_products' => $this->fetchTable('Products')->find()->count(),
            'total_invoices' => $this->fetchTable('Invoices')->find()->count(),
            'generated_at'   => date('Y-m-d H:i:s'),
        ];

        // Detect requested format from URL extension or Accept header
        $requestedFormat = $this->request->getParam('_ext', 'html');

        if ($requestedFormat === 'json' || $this->request->is('json')) {
            // --- JSON RESPONSE ---
            // JsonView serializes the $data variable to JSON
            // No template file needed — JsonView handles encoding
            $this->viewBuilder()->setClassName('Json');
            $this->set(compact('data'));
            $this->viewBuilder()->setOption('serialize', ['data']);
            return;
        }

        if ($requestedFormat === 'xml') {
            // --- XML RESPONSE ---
            // XmlView wraps the $data variable in XML tags
            $this->viewBuilder()->setClassName('Xml');
            $this->set(compact('data'));
            $this->viewBuilder()->setOption('serialize', ['data']);
            return;
        }

        // Default: render HTML template at templates/Reports/summary.php
        $this->set(compact('data'));
    }

    /**
     * exportCsv() — Force download CSV report
     *
     * URL: GET /reports/export-csv?from=2026-01-01&to=2026-12-31
     *
     * PHASE 4: Download response
     * Returns raw CSV content with download headers instead of rendering HTML.
     */
    public function exportCsv(): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        $from = $this->request->getQuery('from', date('Y-m-01'));
        $to   = $this->request->getQuery('to',   date('Y-m-d'));

        // Fetch orders in date range with user names
        $orders = $this->fetchTable('Orders')->find()
            ->contain(['Users'])
            ->where([
                'Orders.created >=' => $from,
                'Orders.created <=' => $to . ' 23:59:59',
            ])
            ->orderBy(['Orders.created' => 'DESC'])
            ->toArray();

        // Build CSV string in memory
        $csv  = "Order Number,Customer,Total,Status,Date\n";
        foreach ($orders as $order) {
            $csv .= sprintf(
                '"%s","%s","%.2f","%s","%s"' . "\n",
                $order->order_number,
                $order->user->username ?? 'Guest',
                $order->total,
                $order->status,
                $order->created->format('Y-m-d')
            );
        }

        // Build download Response — this is a pure PSR-7 response chain
        $filename = 'orders-report-' . $from . '-to-' . $to . '.csv';
        $response = $this->response
            ->withDownload($filename)   // Sets Content-Disposition: attachment
            ->withType('text/csv')      // Sets Content-Type
            ->withStringBody($csv);     // Sets response body

        // Return the response directly — bypasses view rendering
        return $this->send($response);
    }

    /**
     * chartData() — AJAX endpoint for dashboard chart data
     *
     * URL: GET /reports/chart-data (called via jQuery AJAX)
     * Returns: JSON with daily order counts for the last 30 days
     *
     * PHASE 4: AJAX + JSON pattern
     */
    public function chartData(): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        // Only respond to AJAX requests — return 403 otherwise
        if (!$this->request->is('ajax')) {
            $response = $this->response
                ->withStatus(403)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'AJAX only']));
            return $this->send($response);
        }

        // Get daily order counts for last 30 days
        $ordersTable = $this->fetchTable('Orders');
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $count = $ordersTable->find()
                ->where(['DATE(Orders.created)' => $date])
                ->count();
            $data[] = ['date' => $date, 'orders' => $count];
        }

        // Return JSON directly via Response object (no view class needed)
        $response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['chartData' => $data]));

        return $this->send($response);
    }
}
