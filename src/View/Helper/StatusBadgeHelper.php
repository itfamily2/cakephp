<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Phase 12 - StatusBadgeHelper
 * 
 * Generates standardized HTML badges for various status codes
 * used across the ERP (Orders, Invoices, Users, etc.).
 */
class StatusBadgeHelper extends Helper
{
    protected array $helpers = ['Html'];

    /**
     * Render a consistent Bootstrap badge based on the status string.
     */
    public function render(string $status): string
    {
        $status = trim($status);
        $lowerStatus = strtolower($status);
        
        $class = 'badge bg-secondary'; // Default fallback
        
        // Success states
        if (in_array($lowerStatus, ['active', 'paid', 'completed', 'delivered', 'shipped', 'verified'])) {
            $class = 'badge bg-success';
        } 
        // Warning/Processing states
        elseif (in_array($lowerStatus, ['pending', 'processing', 'unpaid', 'low stock'])) {
            $class = 'badge bg-warning text-dark';
        } 
        // Danger/Failed states
        elseif (in_array($lowerStatus, ['cancelled', 'failed', 'inactive', 'refunded', 'banned'])) {
            $class = 'badge bg-danger';
        }
        // Info states
        elseif (in_array($lowerStatus, ['shipped', 'in transit'])) {
            $class = 'badge bg-info text-dark';
        }

        return $this->Html->tag('span', h(ucfirst($status)), ['class' => $class]);
    }

    /**
     * Render a boolean yes/no badge.
     */
    public function booleanStatus(bool $status, string $trueLabel = 'Yes', string $falseLabel = 'No'): string
    {
        if ($status) {
            return $this->Html->tag('span', h($trueLabel), ['class' => 'badge bg-success']);
        }
        return $this->Html->tag('span', h($falseLabel), ['class' => 'badge bg-danger']);
    }
}
