<aside class="sidebar" style="background: #f4f4f4; padding: 15px; border-radius: 5px; height: 100%;">
    <h4>Menu</h4>
    <ul style="list-style: none; padding: 0;">
        <li><?= $this->Html->link('Orders', ['controller' => 'Orders', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link('Products', ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link('Users', ['controller' => 'Users', 'action' => 'index']) ?></li>
        <hr>
        <li><strong>Exports</strong></li>
        <li><?= $this->Html->link('Export JSON', ['controller' => 'Reports', 'action' => 'exportData', '_ext' => 'json']) ?></li>
        <li><?= $this->Html->link('Export XML', ['controller' => 'Reports', 'action' => 'exportData', '_ext' => 'xml']) ?></li>
        <li><?= $this->Html->link('Export CSV', ['controller' => 'Reports', 'action' => 'exportData', '_ext' => 'csv']) ?></li>
    </ul>
</aside>
