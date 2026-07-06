<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property bool $registration_allowed
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\ParentGroup $parent_group
 * @property \App\Model\Entity\GroupUser[] $group_users
 * @property \App\Model\Entity\ChildGroup[] $child_groups
 * @property \App\Model\Entity\Permission[] $permissions
 */
class Group extends Entity
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
        'parent_id' => true,
        'name' => true,
        'registration_allowed' => true,
        'created' => true,
        'modified' => true,
        'parent_group' => true,
        'group_users' => true,
        'child_groups' => true,
        'permissions' => true,
    ];
}
