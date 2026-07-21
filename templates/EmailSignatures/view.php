<!-- Auto-Redesigned View -->

<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Name') ?></th>
                    <td><?= h($emailSignature->name) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('User') ?></th>
                    <td><?= $emailSignature->hasValue('user') ? $this->Html->link($emailSignature->user->username, ['controller' => 'Users', 'action' => 'view', $emailSignature->user->id]) : '' ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($emailSignature->id) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Created') ?></th>
                    <td><?= h($emailSignature->created) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Modified') ?></th>
                    <td><?= h($emailSignature->modified) ?></td>
                </tr>

    </table>
</div>
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
