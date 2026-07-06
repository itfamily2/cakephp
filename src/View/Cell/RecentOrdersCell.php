<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Phase 14 - RecentOrdersCell
 * 
 * View Cells are mini-controllers that can be embedded in any view.
 * They are perfect for widgets, sidebars, or complex UI components 
 * that require database access independent of the main controller.
 */
class RecentOrdersCell extends Cell
{
    protected array $_validCellOptions = [];

    /**
     * Default display method.
     */
    public function display(int $limit = 5)
    {
        $this->loadModel('Orders');
        
        // Fetch the most recent orders
        $recentOrders = $this->Orders->find()
            ->order(['id' => 'DESC'])
            ->limit($limit)
            ->all();

        $this->set('recentOrders', $recentOrders);
    }
}
