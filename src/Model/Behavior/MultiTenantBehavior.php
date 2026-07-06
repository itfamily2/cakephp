<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;

/**
 * Phase 24 - MultiTenantBehavior (Enterprise Features)
 * 
 * Automatically handles Multi-Tenant (Multi-Company) data isolation.
 * 1. Before Save: Injects the current user's `company_id` to ensure records belong to their tenant.
 * 2. Before Find: Automatically appends a WHERE clause for `company_id` to isolate data.
 */
class MultiTenantBehavior extends Behavior
{
    protected array $_defaultConfig = [
        'tenantIdField' => 'company_id',
    ];

    /**
     * Get the active tenant ID from the current session/request.
     */
    protected function getActiveTenantId(): ?int
    {
        $request = Router::getRequest();
        if ($request && $request->getAttribute('identity')) {
            // Assumes the logged-in user identity has a company_id
            return $request->getAttribute('identity')->get('company_id');
        }
        return null;
    }

    /**
     * Automatically filter SELECT queries to only show data for the active company tenant.
     */
    public function beforeFind(EventInterface $event, SelectQuery $query, \ArrayObject $options, $primary): void
    {
        $tenantId = $this->getActiveTenantId();

        // If a tenant is active, isolate the query
        if ($tenantId !== null) {
            $field = $this->getTable()->getAlias() . '.' . $this->getConfig('tenantIdField');
            $query->where([$field => $tenantId]);
        }
    }

    /**
     * Automatically inject the active tenant ID when creating new records.
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, \ArrayObject $options): void
    {
        $tenantId = $this->getActiveTenantId();
        $field = $this->getConfig('tenantIdField');

        if ($tenantId !== null && $entity->isNew() && !$entity->has($field)) {
            $entity->set($field, $tenantId);
        }
    }
}
