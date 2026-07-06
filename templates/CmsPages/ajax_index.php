<div class="table-responsive">
    <table class="table custom-table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th><?= $this->Paginator->sort('title', 'Title') ?></th>
                <th><?= $this->Paginator->sort('slug', 'Slug') ?></th>
                <th><?= $this->Paginator->sort('meta_title', 'Meta Title') ?></th>
                <th><?= $this->Paginator->sort('created', 'Created') ?></th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($cmsPages) === 0): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No CMS pages found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($cmsPages as $cmsPage): ?>
                    <tr>
                        <td><?= h($cmsPage->id) ?></td>
                        <td><span class="fw-bold text-white"><?= h($cmsPage->title) ?></span></td>
                        <td><code>/page/<?= h($cmsPage->slug) ?></code></td>
                        <td><?= h($cmsPage->meta_title ?: 'N/A') ?></td>
                        <td><?= h($cmsPage->created->format('Y-m-d H:i')) ?></td>
                        <td class="text-end">
                            <a href="<?= $this->Url->build(['action' => 'view', $cmsPage->id]) ?>" class="btn btn-outline-info btn-sm me-1" title="View"><i class="fa-solid fa-eye"></i></a>
                            <a href="<?= $this->Url->build(['action' => 'edit', $cmsPage->id]) ?>" class="btn btn-outline-primary btn-sm me-1" title="Edit"><i class="fa-solid fa-pencil"></i></a>
                            <?= $this->Form->postLink(
                                '<i class="fa-solid fa-trash"></i>',
                                ['action' => 'delete', $cmsPage->id],
                                [
                                    'escapeTitle' => false,
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'title' => 'Delete',
                                    'confirm' => __('Are you sure you want to delete CMS page {0}?', $cmsPage->title)
                                ]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2">
    <div class="text-muted small">
        <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total')) ?>
    </div>
    <ul class="pagination pagination-sm m-0">
        <?= $this->Paginator->first('<< First') ?>
        <?= $this->Paginator->prev('< Prev') ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('Next >') ?>
        <?= $this->Paginator->last('Last >>') ?>
    </ul>
</div>
