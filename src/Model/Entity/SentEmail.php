<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SentEmail Entity
 *
 * @property int $id
 * @property int|null $email_template_id
 * @property int|null $email_signature_id
 * @property string $recipient_email
 * @property string $subject
 * @property string $body
 * @property \Cake\I18n\DateTime $sent_time
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\EmailTemplate $email_template
 * @property \App\Model\Entity\EmailSignature $email_signature
 */
class SentEmail extends Entity
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
        'email_template_id' => true,
        'email_signature_id' => true,
        'recipient_email' => true,
        'subject' => true,
        'body' => true,
        'sent_time' => true,
        'created' => true,
        'modified' => true,
        'email_template' => true,
        'email_signature' => true,
    ];
}
