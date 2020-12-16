<?php 
use \Adnduweb\Ci4Admin\Libraries\Theme; ?>
<?= $this->extend('Adnduweb\Ci4Admin\themes\metronic\__layouts\layout_1') ?>
<?= $this->section('main') ?>
<!-- end:: Header -->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <?= $this->include('Adnduweb\Ci4Admin\themes\metronic\__partials\kt_list_toolbar') ?>

    <!-- begin:: Content -->
    <div id="ContentMedias" class="d-flex flex-column-fluid ">
		<div class="container-fluid">
			<div class="card card-custom gutter-b">
				<div class="card-body bg-light">
						<div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">				
							<div class="btn-group mr-2" role="group" aria-label="Action group">
								<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#dropzoneCollapse" aria-expanded="false" aria-controls="dropzoneCollapse">
									<?= Theme::getSVG('assets/media/svg/icons/Design/Flatten.svg', 'svg-icon svg-icon-sm', true); ?> 
									<?= lang('Medias.add_media'); ?>
								</button>
								<a type="button" class="btn btn-icon btn-danger" href="<?= base_url(CI_AREA_ADMIN . '/medias/removeall'); ?>">
									<?= Theme::getSVG('assets/media/svg/icons/Home/Trash.svg', 'svg-icon svg-icon-sm', true); ?> 
								</a>
							</div>
						</div>

						<h1><?= lang('Medias.manage_of_files'); ?></h1>

						<?= view('Adnduweb\Ci4Media\Views\Dropzone\zone') ?>


						<!-- <form class="form-inline mb-3" name="files-search" method="get" action="<?= current_url() ?>">
							<div class="input-group">
								<input name="search" type="search" class="form-control" id="files-search" placeholder="Search" value="<?= $search ?>">
								<div class="input-group-append">
									<button type="submit" class="btn btn-primary">Search</button>
								</div>
							</div>
						</form> -->

						


						<div id="files-wrapper">
							<?php if (empty($files)): ?>
							<!-- <p>
								You have no files! Would you like to
								<a class="dropzone-button" href="<?= site_url('files/new') ?>" data-toggle="modal" data-target="#dropzoneModal">add some now</a>?
							</p> -->
							<div id="imageManager"></div>

							<?php else: ?>

							
							<div id="imageManager">
								<?= view('Adnduweb\Ci4Media\Views\Forms\files') ?>
							</div>
							
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?= view('Adnduweb\Ci4Media\Views\Dropzone\modal') ?>

	<!-- begin::Outils de gestion de média -->
	<div id="imageManager_edition" class="imageManager_edition"></div>
	<!-- end::Outils de gestion de média -->

<?= $this->endSection() ?>
<?= $this->section('AfterExtraJs') ?>

	<?= view(config('Medias')->views['dropzone']) ?>

<?= $this->endSection() ?>
