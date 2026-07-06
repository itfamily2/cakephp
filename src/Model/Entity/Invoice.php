<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Invoice Entity
 *
 * @property int $id
 * @property int $order_id
 * @property string $invoice_number
 * @property string $amount
 * @property string $tax
 * @property string $discount
 * @property string $status
 * @property \Cake\I18n\Date|null $due_date
 * @property string|null $notes
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Order $order
 */
class Invoice extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'order_id' => true,
        'invoice_number' => true,
        'amount' => true,
        'tax' => true,
        'discount' => true,
        'status' => true,
        'due_date' => true,
        'notes' => true,
        'created' => true,
        'modified' => true,
        'order' => true,
    ];
}
