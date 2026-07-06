<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Query\SelectQuery;
use Cake\Validation\Validator;

/**
 * Phase 17 - NotificationsTable
 * In-app notification system.
 */
class NotificationsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('notifications');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp', ['events' => ['Model.beforeSave' => ['created' => 'new']]]);
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('title', 'Notification title is required.');
        $validator->notEmptyString('message', 'Notification message is required.');
        $validator->inList('type', ['info', 'success', 'warning', 'error'], 'Invalid notification type.');
        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }

    /** find('unread') - Fetch unread notifications for a user */
    public function findUnread(SelectQuery $query, array $options): SelectQuery
    {
        return $query->where(['Notifications.is_read' => false]);
    }
}
