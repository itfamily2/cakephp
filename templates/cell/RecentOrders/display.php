<div class="recent-orders-cell" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
    <h3>Recent Orders</h3>
    
    <?php if ($recentOrders->isEmpty()): ?>
        <p>No recent orders found.</p>
    <?php else: ?>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $order): ?>
                <tr>
                    <td><?= h($order->order_number) ?></td>
                    <td><?= $this->StatusBadge->render($order->status ?? 'Pending') ?></td>
                    <td><?= $this->ErpFormat->currency((float)$order->total) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
