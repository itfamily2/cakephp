<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContactEnquiry Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property string|null $reply_message
 * @property string $reply_status
 * @property int|null $assigned_staff_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\AssignedStaff $assigned_staff
 */
class ContactEnquiry extends Entity
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
        'name' => true,
        'email' => true,
        'subject' => true,
        'message' => true,
        'reply_message' => true,
        'reply_status' => true,
        'assigned_staff_id' => true,
        'created' => true,
        'modified' => true,
        'assigned_staff' => true,
    ];
}
