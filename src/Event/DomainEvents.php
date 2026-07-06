<?php
declare(strict_types=1);

/**
 * Phase 10 — Custom Domain Event Constants
 *
 * WHY A CONSTANTS CLASS:
 *   Instead of scattering string event names like 'Order.created' across
 *   dozens of files, we centralize them here. This gives us:
 *     1. Autocomplete in IDE — no typos
 *     2. Single source of truth — grep DomainEvents:: to find all event usage
 *     3. Refactoring safety — rename once, changes everywhere
 *
 * NAMING CONVENTION:
 *   '{Entity}.{pastTenseVerb}' — e.g. 'User.registered', 'Order.created'
 *   Past tense because events describe something that already happened.
 *
 * INTERVIEW: "I always define custom event names as class constants.
 *   It prevents string mismatches between dispatcher and listener,
 *   and makes the full event catalog grep-able from one file."
 */
namespace App\Event;

final class DomainEvents
{
    // =========================================================================
    // USER EVENTS
    // =========================================================================
    /**
     * Dispatched after a new user account is successfully created and committed.
     * Data: ['user' => EntityInterface] — the newly created User entity.
     * Typical reactions: send welcome email, create default preferences,
     *   push to analytics/CRM, assign onboarding tasks.
     */
    public const USER_REGISTERED = 'User.registered';

    // =========================================================================
    // ORDER EVENTS
    // =========================================================================
    /**
     * Dispatched after a new order is saved and committed to the database.
     * Data: ['order' => EntityInterface] — the Order entity with items.
     * Typical reactions: send confirmation email, notify warehouse,
     *   create invoice, trigger stock reservation.
     */
    public const ORDER_CREATED = 'Order.created';

    // =========================================================================
    // INVOICE EVENTS
    // =========================================================================
    /**
     * Dispatched when an invoice status changes to 'paid'.
     * Data: ['invoice' => EntityInterface] — the Invoice entity.
     * Typical reactions: release shipment, update accounting ledger,
     *   send payment receipt email, update order status to 'paid'.
     */
    public const INVOICE_PAID = 'Invoice.paid';

    // =========================================================================
    // EMAIL EVENTS
    // =========================================================================
    /**
     * Dispatched after an email is successfully sent and logged.
     * Data: ['email' => EntityInterface] — the SentEmail entity.
     * Typical reactions: update delivery tracking, increment send counters,
     *   log for compliance/audit trail.
     */
    public const EMAIL_SENT = 'Email.sent';

    // =========================================================================
    // PRODUCT / INVENTORY EVENTS
    // =========================================================================
    /**
     * Dispatched when a product's stock falls below the low-stock threshold.
     * Data: ['product' => EntityInterface, 'threshold' => int] — the Product entity.
     * Typical reactions: send alert to procurement team, create purchase order,
     *   flag product in admin dashboard, send Slack/webhook notification.
     */
    public const PRODUCT_LOW_STOCK = 'Product.lowStock';
}
