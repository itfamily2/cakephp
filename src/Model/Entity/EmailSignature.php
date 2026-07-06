<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmailSignature Entity
 *
 * @property int $id
 * @property string $name
 * @property string $body
 * @property int|null $user_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ScheduledEmail[] $scheduled_emails
 * @property \App\Model\Entity\SentEmail[] $sent_emails
 */
class EmailSignature extends Entity
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
        'body' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'scheduled_emails' => true,
        'sent_emails' => true,
    ];
}
