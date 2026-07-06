<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AuditLog Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $table_name
 * @property int $row_id
 * @property string $action
 * @property string|null $old_values
 * @property string|null $new_values
 * @property string|null $ip_address
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\User $user
 */
class AuditLog extends Entity
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
        'user_id' => true,
        'table_name' => true,
        'row_id' => true,
        'action' => true,
        'old_values' => true,
        'new_values' => true,
        'ip_address' => true,
        'created' => true,
        'user' => true,
    ];
}
