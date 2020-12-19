<?php $pager->setSurroundCount(2) ?>

<div class="d-flex justify-content-between align-items-center flex-wrap">
<div class="d-flex flex-wrap py-2 mr-3">
	<?php if ($pager->hasPrevious()) : ?>
			<a class="btn btn-icon btn-sm btn-light mr-2 my-1" href="<?= $pager->getFirst() ?>" aria-label="First">
				<span aria-hidden="true">First</span>
			</a>
			<a class="btn btn-icon btn-sm btn-light mr-2 my-1" href="<?= $pager->getPrevious() ?>" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
			</a>
	<?php endif ?>

	<?php foreach ($pager->links() as $link) : ?>
		<a class="btn btn-icon btn-sm border-0 btn-light btn-hover-primary <?= $link['active'] ? 'active' : '' ?> mr-2 my-1" href="<?= $link['uri'] ?>">
			<?= $link['title'] ?>
		</a>
	<?php endforeach ?>

	<?php if ($pager->hasNext()) : ?>
		<a class="btn btn-icon btn-sm btn-light mr-2 my-1" href="<?= $pager->getNext() ?>" aria-label="Previous">
			<span aria-hidden="true">&raquo;</span>
		</a>
		<a class="btn btn-icon btn-sm btn-light mr-2 my-1" href="<?= $pager->getLast() ?>" aria-label="Last">
			<span aria-hidden="true">Last</span>
		</a>
	<?php endif ?>
	</div>
</div>
