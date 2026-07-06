<?php
$this->assign('title', 'View/Edit Log File: ' . $fileName);
?>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card glass-card p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="fw-bold text-white m-0">
                        <i class="fa-solid fa-file-invoice text-primary me-2"></i><?= h($fileName) ?>
                    </h4>
                    <span class="text-muted small">Size: <?= number_format($size) ?> Bytes</span>
                </div>
                <div>
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Log Files
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card glass-card p-4">
            <?= $this->Form->create(null) ?>
            <div class="mb-3">
                <label for="logContent" class="form-label text-white fw-bold">Log File Content</label>
                <textarea id="logContent" name="content" class="form-control bg-dark text-light border-secondary font-monospace" rows="20" style="font-size: 0.85rem; line-height: 1.4;"><?= h($content) ?></textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save me-1"></i> Save Changes
                </button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
