
	<?php if (empty($files)): ?>
	<p><?= lang('Medias.No files to display'); ?></p>
	<?php else: ?>
	<div class="card-deck d-flex">

		<?php foreach ($files as $file): ?>
		<div class="cardImage mb-4 file-item" data-uuid-media="<?= $file->uuid ; ?>"> 
			<div class="bgi-no-repeat bgi-size-cover rounded min-h-180px w-100 w-180px h-150px mr-5 mb-1 mb-md-0 bg-primary-o-10">
				<div class="attachment-preview js--select-attachment subtype-<?= $file->getExtension(); ?> <?= $file->getOrientation(); ?>">
					<div class="thumbnail">
						<div class="centered">
							<div class="card-toolbar">
								<a href="javascript:;" data-uuid-media="<?= $file->uuid ; ?>" class="btn select-image btn-icon btn-circle btn-sm btn-light-primary mr-1" data-card-tool="toggle">
									<i class="la la-pencil icon-nm"></i>
								</a>
								<a href="#" data-imagemanager="reload" data-uuid="<?= $file->getUuid(); ?>" class="btn btn-icon btn-circle btn-sm btn-light-danger deleteFileMedia" data-card-tool="remove">
									<i class="la la-close icon-nm"></i>
								</a>
							</div>
							<img data-uuid-media="<?= $file->uuid ; ?>" src="<?= img_data($file->getThumbnail()) ?>" class="card-img-top img-thumbnail select-image" alt="<?= $file->filename ?>">
						</div>
						<?php if ($file->getBTitle()) { ?>
								<div class="nameFile"><?= $file->getBTitle(); ?></div>
							<?php } ?>
							<?php if ($file->getOrientation() == 'square') { ?>
								<div class="nameFile"><?= $file->filename; ?></div>
							<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>

	</div>
	<?php endif; ?>
