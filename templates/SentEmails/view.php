<!-- Auto-Redesigned View -->

<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Email Template') ?></th>
                    <td><?= $sentEmail->hasValue('email_template') ? $this->Html->link($sentEmail->email_template->name, ['controller' => 'EmailTemplates', 'action' => 'view', $sentEmail->email_template->id]) : '' ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Email Signature') ?></th>
                    <td><?= $sentEmail->hasValue('email_signature') ? $this->Html->link($sentEmail->email_signature->name, ['controller' => 'EmailSignatures', 'action' => 'view', $sentEmail->email_signature->id]) : '' ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Recipient Email') ?></th>
                    <td><?= h($sentEmail->recipient_email) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Subject') ?></th>
                    <td><?= h($sentEmail->subject) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($sentEmail->id) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Sent Time') ?></th>
                    <td><?= h($sentEmail->sent_time) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Created') ?></th>
                    <td><?= h($sentEmail->created) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Modified') ?></th>
                    <td><?= h($sentEmail->modified) ?></td>
                </tr>

    </table>
</div>
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
