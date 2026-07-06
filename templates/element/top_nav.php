<nav class="top-nav">
    <div class="top-nav-title">
        <a href="<?= $this->Url->build('/') ?>"><span>Yeelo</span>ERP</a>
    </div>
    <div class="top-nav-links">
        <?= $this->Html->link('Dashboard', ['controller' => 'Reports', 'action' => 'dashboard']) ?>
        <?= $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout']) ?>
    </div>
</nav>
