<!-- Auto-Redesigned View -->

<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Email Template') ?></th>
                    <td><?= $scheduledEmail->hasValue('email_template') ? $this->Html->link($scheduledEmail->email_template->name, ['controller' => 'EmailTemplates', 'action' => 'view', $scheduledEmail->email_template->id]) : '' ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Email Signature') ?></th>
                    <td><?= $scheduledEmail->hasValue('email_signature') ? $this->Html->link($scheduledEmail->email_signature->name, ['controller' => 'EmailSignatures', 'action' => 'view', $scheduledEmail->email_signature->id]) : '' ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Recipient Email') ?></th>
                    <td><?= h($scheduledEmail->recipient_email) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Subject') ?></th>
                    <td><?= h($scheduledEmail->subject) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Status') ?></th>
                    <td><?= h($scheduledEmail->status) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($scheduledEmail->id) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Scheduled Time') ?></th>
                    <td><?= h($scheduledEmail->scheduled_time) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Sent Time') ?></th>
                    <td><?= h($scheduledEmail->sent_time) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Created') ?></th>
                    <td><?= h($scheduledEmail->created) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Modified') ?></th>
                    <td><?= h($scheduledEmail->modified) ?></td>
                </tr>

    </table>
</div>
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
