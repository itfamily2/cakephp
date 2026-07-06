<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Phase 12 - ErpFormatHelper
 * 
 * A custom helper designed specifically for the ERP to centralize 
 * and standardize the formatting of currencies, dates, and text strings.
 * It leverages core CakePHP helpers internally.
 */
class ErpFormatHelper extends Helper
{
    /**
     * Helpers required by this helper.
     * We load the core CakePHP helpers to utilize their functionality.
     */
    protected array $helpers = ['Number', 'Time', 'Text', 'Html'];

    /**
     * Standardize currency formatting across the ERP.
     */
    public function currency(float $amount, string $currency = 'USD'): string
    {
        // Wrapper around Number helper to ensure consistent formatting
        return $this->Number->currency($amount, $currency, [
            'places' => 2,
            'locale' => 'en_US'
        ]);
    }

    /**
     * Standardize date formatting across the ERP.
     */
    public function date($date): string
    {
        if (!$date) {
            return 'N/A';
        }
        return $this->Time->format($date, 'yyyy-MM-dd HH:mm:ss');
    }

    /**
     * Generate a truncated text with a "read more" tooltip for long descriptions.
     */
    public function truncatedText(string $text, int $length = 50): string
    {
        if (strlen($text) <= $length) {
            return h($text);
        }

        $shortText = $this->Text->truncate($text, $length, [
            'ellipsis' => '...',
            'exact' => false
        ]);

        return $this->Html->tag('span', $shortText, [
            'title' => $text,
            'data-bs-toggle' => 'tooltip'
        ]);
    }
}
