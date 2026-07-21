<!-- Auto-Redesigned View -->

<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
<tr>
                    <th class="bg-light text-muted w-25"><?= __('User') ?></th>
                    <td><?= $auditLog->hasValue('user') ? $this->Html->link($auditLog->user->username, ['controller' => 'Users', 'action' => 'view', $auditLog->user->id]) : '' ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Table Name') ?></th>
                    <td><?= h($auditLog->table_name) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Action') ?></th>
                    <td><?= h($auditLog->action) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Ip Address') ?></th>
                    <td><?= h($auditLog->ip_address) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($auditLog->id) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Row Id') ?></th>
                    <td><?= $this->Number->format($auditLog->row_id) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Created') ?></th>
                    <td><?= h($auditLog->created) ?></td>
                </tr>

    </table>
</div>
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
