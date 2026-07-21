<!-- Auto-Redesigned View -->

<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0">
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Key') ?></th>
                    <td><?= h($setting->key) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Input Type') ?></th>
                    <td><?= h($setting->input_type) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($setting->id) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Created') ?></th>
                    <td><?= h($setting->created) ?></td>
                </tr>
<tr>
                    <th class="bg-light text-muted w-25"><?= __('Modified') ?></th>
                    <td><?= h($setting->modified) ?></td>
                </tr>

    </table>
</div>
<div class="text-end mt-4 pt-3" style="border-top:1px solid var(--border-color);">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
