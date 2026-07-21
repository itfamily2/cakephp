<?php $this->assign('title', 'Settings'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-gears text-warning me-2"></i>System Settings</h4>
        <p class="text-muted mb-0">Configure application-wide settings, preferences and integrations.</p>
    </div>
    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>New Setting
    </a>
</div>
<!-- Group settings by category -->
<?php
$groups = [];
foreach ($settings as $setting) {
    $groups[$setting->group ?? 'General'][] = $setting;
}
?>
<?php foreach ($groups as $groupName => $groupSettings): ?>
<div class="glass-card mb-4">
    <div class="p-4 border-bottom d-flex align-items-center gap-2" style="border-color:var(--border-color)!important;">
        <i class="fa-solid fa-folder-open text-warning me-2"></i>
        <h6 class="fw-bold mb-0"><?= h($groupName) ?></h6>
        <span class="badge bg-secondary bg-opacity-30 text-muted ms-auto"><?= count($groupSettings) ?> settings</span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;">
                    <th class="ps-4 py-3">Key</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Description</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groupSettings as $setting): ?>
                <tr>
                    <td class="ps-4"><code class="text-warning"><?= h($setting->key) ?></code></td>
                    <td><span class="badge bg-info bg-opacity-20 text-info small"><?= h($setting->type ?? 'string') ?></span></td>
                    <td>
                        <?php $val = $setting->value; ?>
                        <?php if (strlen($val) > 60): ?>
                            <span class="text-muted small font-monospace"><?= h(substr($val, 0, 60)) ?>...</span>
                        <?php elseif ($val === '1' || $val === 'true'): ?>
                            <span class="badge bg-success bg-opacity-20 text-success"><i class="fa-solid fa-check me-1"></i>Enabled</span>
                        <?php elseif ($val === '0' || $val === 'false'): ?>
                            <span class="badge bg-danger bg-opacity-20 text-danger"><i class="fa-solid fa-xmark me-1"></i>Disabled</span>
                        <?php else: ?>
                            <span class="text-white small"><?= h($val) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= h($setting->description ?? '—') ?></td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <?= $this->Html->link('<i class="fa-solid fa-pen"></i>', ['action' => 'edit', $setting->id], ['class' => 'btn btn-sm btn-outline-primary ajax-modal-link', 'escape' => false]) ?>
                            <?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $setting->id], ['class' => 'btn btn-sm btn-outline-danger', 'escape' => false, 'confirm' => 'Delete setting "' . h($setting->key) . '"?']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endforeach; ?>
<?php if (empty($settings) || !count($settings)): ?>
<div class="glass-card p-5 text-center text-muted">
    <i class="fa-solid fa-gears fa-3x d-block mb-3 opacity-30"></i>
    <p>No settings found. <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="text-primary">Add a setting</a></p>
</div>
<?php endif; ?>