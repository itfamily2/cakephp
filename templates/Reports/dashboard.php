<?php
// Set the page title block which is fetched in the layout
$this->assign('title', 'Admin Dashboard Overview');
?>

<div class="dashboard-index">
    <h2>Welcome to the ERP Dashboard</h2>
    <p>This view demonstrates Layouts, Elements, and View Cells.</p>
    
    <hr>

    <div class="row">
        <div class="column column-100">
            <!-- 
                CELL INVOCATION
                Calling the RecentOrdersCell and passing 3 as the $limit argument 
            -->
            <?= $this->cell('RecentOrders', [3]) ?>
        </div>
    </div>
</div>
