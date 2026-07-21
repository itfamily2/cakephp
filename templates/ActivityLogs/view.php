<!-- Auto-Redesigned View -->

<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
<tr>
                    <th class="bg-light text-muted w-25"><?= __('User') ?></th>
                    <td><?= $activityLog->hasValue('user') ? $this->Html->link($activityLog->user->username, ['controller' => 'Users', 'action' => 'view', $activityLog->user->id]) : '' ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Action') ?></th>
                    <td><?= h($activityLog->action) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Ip Address') ?></th>
                    <td><?= h($activityLog->ip_address) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('User Agent') ?></th>
                    <td><?= h($activityLog->user_agent) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($activityLog->id) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Created') ?></th>
                    <td><?= h($activityLog->created) ?></td>
                </tr>

    </table>
</div>
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
