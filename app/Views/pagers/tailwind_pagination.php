<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation" class="flex items-center space-x-2">
    <?php if ($pager->hasPrevious()) : ?>
        <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="p-2 rounded-lg border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-500 dark:text-slate-400 transition-colors">
            <span aria-hidden="true">««</span>
        </a>
        <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" class="p-2 rounded-lg border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-500 dark:text-slate-400 transition-colors">
            <span aria-hidden="true">«</span>
        </a>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <a href="<?= $link['uri'] ?>" class="px-4 py-2 rounded-lg border font-medium transition-all <?= $link['active'] ? 'bg-blue-600 border-blue-600 dark:bg-blue-700 dark:border-blue-700 text-white shadow-lg shadow-blue-200 dark:shadow-none' : 'bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800 hover:border-gray-300 dark:hover:border-slate-700' ?>">
            <?= $link['title'] ?>
        </a>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="p-2 rounded-lg border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-500 dark:text-slate-400 transition-colors">
            <span aria-hidden="true">»</span>
        </a>
        <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" class="p-2 rounded-lg border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-500 dark:text-slate-400 transition-colors">
            <span aria-hidden="true">»»</span>
        </a>
    <?php endif ?>
</nav>
