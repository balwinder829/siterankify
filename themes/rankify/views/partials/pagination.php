<?php defined('ALTUMCODE') || die() ?>

<?php $pages = $data->paginator->getPages(); ?>

<?php if(settings()->main->display_pagination_when_no_pages || count($pages)): ?>
<div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-2xs font-sans">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
        <p class="text-xs text-slate-500 m-0">
            <?= sprintf(l('global.pagination.results'), '<strong class="font-bold text-slate-700">' . nr($data->paginator->getCurrentPageFirstItem()) . '</strong>', '<strong class="font-bold text-slate-700">' . nr($data->paginator->getCurrentPageLastItem()) . '</strong>', '<strong class="font-bold text-slate-700">' . nr($data->paginator->getTotalItems()) . '</strong>') ?>
        </p>

        <?php if (!empty($pages)): ?>
            <div class="flex items-center gap-1 overflow-x-auto max-w-full py-0.5">
                <?php if($data->paginator->getPrevUrl()): ?>
                    <a href="<?= $data->paginator->getPrevUrl(); ?>" class="h-8 w-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200/80 flex items-center justify-center text-xs font-bold text-decoration-none transition-colors" aria-label="<?= l('global.pagination.previous') ?>">‹</a>
                <?php endif; ?>

                <?php foreach($pages as $page): ?>
                    <?php if($page['url']): ?>
                        <a href="<?= $page['url']; ?>" class="h-8 min-w-[32px] px-2 rounded-lg text-xs font-bold flex items-center justify-center text-decoration-none transition-colors <?= $page['isCurrent'] ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80'; ?>">
                            <?= $page['num']; ?>
                        </a>
                    <?php else: ?>
                        <span class="h-8 min-w-[32px] px-2 rounded-lg text-xs font-medium text-slate-400 flex items-center justify-center select-none"><?= $page['num']; ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if($data->paginator->getNextUrl()): ?>
                    <a href="<?= $data->paginator->getNextUrl(); ?>" class="h-8 w-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200/80 flex items-center justify-center text-xs font-bold text-decoration-none transition-colors" aria-label="<?= l('global.pagination.next') ?>">›</a>
                <?php endif; ?>
            </div>
        <?php endif ?>
    </div>
</div>
<?php endif ?>
