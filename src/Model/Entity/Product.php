<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity — Phase 5: Virtual Fields + Accessors
 *
 * Demonstrates virtual fields, multiple accessors, and protection patterns.
 *
 * VIRTUAL FIELDS EXPLAINED:
 *   A virtual field is a property that DOESN'T exist as a column in the
 *   database but is computed at runtime from other fields.
 *
 *   Examples here:
 *     $product->price_formatted    → "₹1,299.00" (formatted price string)
 *     $product->is_in_stock        → true/false (derived from stock column)
 *     $product->stock_status_label → "In Stock" / "Low Stock" / "Out of Stock"
 *     $product->discounted_price   → calculated with 10% discount applied
 *
 *   These appear as normal properties in templates, API responses, and
 *   anywhere the entity is used.
 */
class Product extends Entity
{
    protected array $_accessible = [
        'category_id'  => true,
        'brand_id'     => true,
        'name'         => true,
        'slug'         => true,
        'sku'          => true,
        'price'        => true,
        'stock'        => true,
        'description'  => true,
        'deleted_at'   => true,
        'created'      => true,
        'modified'     => true,
        // Associations
        'category'     => true,
        'brand'        => true,
        'order_items'  => true,
    ];

    protected array $_hidden = [
        'deleted_at', // Never expose soft-delete timestamp in API responses
    ];

    // =========================================================================
    // VIRTUAL FIELDS — Phase 5
    // =========================================================================
    // Each _getXxx() method creates a virtual property named $entity->xxx
    // Virtual fields are NOT included in toArray() by default unless you
    // add them to $_virtual array below.
    // =========================================================================

    /**
     * Include these virtual fields in toArray() and JSON serialization.
     */
    protected array $_virtual = [
        'price_formatted',
        'is_in_stock',
        'stock_status_label',
    ];

    /**
     * _getPriceFormatted() — Virtual: formatted price with currency symbol.
     * Access: $product->price_formatted
     * Included in JSON: Yes (listed in $_virtual)
     */
    protected function _getPriceFormatted(): string
    {
        $price = $this->_fields['price'] ?? 0;
        return '₹' . number_format((float)$price, 2);
    }

    /**
     * _getIsInStock() — Virtual: boolean stock availability check.
     * Access: $product->is_in_stock
     */
    protected function _getIsInStock(): bool
    {
        return ($this->_fields['stock'] ?? 0) > 0;
    }

    /**
     * _getStockStatusLabel() — Virtual: human-readable stock status.
     * Access: $product->stock_status_label
     *
     * INTERVIEW: "This virtual field encapsulates a business rule — the
     *   threshold for 'Low Stock' — inside the Entity. If the threshold
     *   changes from 5 to 10, I update it in one place here."
     */
    protected function _getStockStatusLabel(): string
    {
        $stock = $this->_fields['stock'] ?? 0;

        if ($stock <= 0)   return 'Out of Stock';
        if ($stock <= 5)   return 'Low Stock';
        return 'In Stock';
    }

    /**
     * _getDiscountedPrice() — Virtual: price with 10% discount.
     * Access: $product->discounted_price
     * NOT included in JSON (not in $_virtual) — used only in PHP code.
     */
    protected function _getDiscountedPrice(): float
    {
        $price = (float)($this->_fields['price'] ?? 0);
        return round($price * 0.90, 2); // 10% off
    }

    /**
     * _setName() — Mutator: capitalize product name on save.
     */
    protected function _setName(string $name): string
    {
        return ucwords(strtolower(trim($name)));
    }

    /**
     * _setSku() — Mutator: force uppercase SKU codes.
     */
    protected function _setSku(string $sku): string
    {
        return strtoupper(trim($sku));
    }
}
