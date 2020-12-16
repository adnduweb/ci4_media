<?php
$exports = $file->getExports();

// Make sure there is something to display
if (empty($exports) && $access === 'display')
{
	return;
}
?>
	<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="export-<?= $file->id ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="fas fa-share-square"></i> Actions
	</button>
	<div class="dropdown-menu" aria-labelledby="export-<?= $file->id ?>">

		<?php if (! empty($exports)): ?>
		<h6 class="dropdown-header">Send To</h6>
		<?php foreach ($exports as $class): ?>
	
		<?php $export = new $class(); ?>
		<?php if ($export->ajax): ?>
		<a class="dropdown-item" href="<?= site_url('files/export/' . $export->slug . '/' . $file->id) ?>" onclick="$('#globalModal .modal-body').load('<?= site_url(CI_AREA_ADMIN . '/medias/export/' . $export->slug . '/' . $file->id) ?>'); $('#globalModal').modal(); return false;"><?= $export->name ?></a>
		
		<?php else: ?>
		<a class="dropdown-item" href="<?= site_url('files/export/' . $export->slug . '/' . $file->id) ?>"><?= $export->name ?></a>
		
		<?php endif; ?>
		<?php endforeach; ?>
		<?php endif; ?>

		<?php if ($access === 'manage'): ?>
		<div class="dropdown-divider"></div>
		<a class="dropdown-item select-image" data-uuid-media="<?= $file->uuid ; ?>" href="<?= site_url(CI_AREA_ADMIN . '/medias/rename/' . $file->id) ?>" onclick="$('#globalModal .modal-body').load('<?= site_url(CI_AREA_ADMIN . '/medias/rename/' . $file->id) ?>'); $('#globalModal').modal(); return false;"><?= lang('Core.edit'); ?></a>
		<a class="dropdown-item" href="<?= site_url(CI_AREA_ADMIN . '/medias/removeFile/' . $file->uuid) ?>"><?= lang('Core.delete'); ?></a>
		<?php endif; ?>

	</div>
