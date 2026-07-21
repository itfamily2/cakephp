<!-- Auto-Redesigned View -->

<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Name') ?></th>
                    <td><?= h($contactEnquiry->name) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Email') ?></th>
                    <td><?= h($contactEnquiry->email) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Subject') ?></th>
                    <td><?= h($contactEnquiry->subject) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Reply Status') ?></th>
                    <td><?= h($contactEnquiry->reply_status) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Assigned Staff') ?></th>
                    <td><?= $contactEnquiry->hasValue('assigned_staff') ? $this->Html->link($contactEnquiry->assigned_staff->username, ['controller' => 'Users', 'action' => 'view', $contactEnquiry->assigned_staff->id]) : '' ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($contactEnquiry->id) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Created') ?></th>
                    <td><?= h($contactEnquiry->created) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Modified') ?></th>
                    <td><?= h($contactEnquiry->modified) ?></td>
                </tr>

    </table>
</div>
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
