<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>" class="d-inline-block">
    <ul class="pagination pagination-sm m-0 shadow-sm">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" title="Halaman Pertama">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" title="Sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link" aria-hidden="true">
                    <i class="fas fa-angle-double-left"></i>
                </span>
            </li>
            <li class="page-item disabled">
                <span class="page-link" aria-hidden="true">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>" <?= $link['active'] ? 'aria-current="page"' : '' ?>>
                <a class="page-link" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" title="Berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" title="Halaman Terakhir">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link" aria-hidden="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </li>
            <li class="page-item disabled">
                <span class="page-link" aria-hidden="true">
                    <i class="fas fa-angle-double-right"></i>
                </span>
            </li>
        <?php endif ?>
    </ul>
</nav>
