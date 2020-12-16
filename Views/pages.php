
	<div class="row mb-10 mt-10 bg-secondary">
		<div class="col-sm-7">
			<?= $pager->only(['search'])->links('default', 'files_bootstrap') ?>
		</div>
		<div class="col-sm-2"></div>
		<div class="col-3 float-right d-flex align-items-center">
			<form name="files-pages" method="get" action="<?= current_url() ?>" class="w-50 mr-10">
				<label class="sr-only" for="perPage">Per page</label>
				<select class="form-control" name="perPage" id="perPage" onchange="this.form.submit();">
					<?php foreach ([5, 10, 25, 50, 100, 200] as $num): ?>
					<option value="<?= $num ?>" <?= $num === $perPage ? 'selected' : '' ?>><?= $num ?></option>
					<?php endforeach; ?>
				</select>
			</form>

			<div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">				
				<div class="btn-group mr-2" role="group" aria-label="Format group">
					<a class="btn <?= $format === 'cards' ? 'btn-secondary active' : 'btn-outline-secondary' ?> btn-icon" href="<?= site_url(CI_AREA_ADMIN . "/medias/{$source}") ?>?format=cards" role="button"><i class="la la-th-large"></i></a>
					<a class="btn <?= $format === 'list' ? 'btn-secondary active' : 'btn-outline-secondary' ?> btn-icon" href="<?= site_url(CI_AREA_ADMIN . "/medias/{$source}") ?>?format=list" role="button"><i class="la la-list"></i></a>
					<a class="btn <?= $format === 'select' ? 'btn-secondary active' : 'btn-outline-secondary' ?> btn-icon" href="<?= site_url(CI_AREA_ADMIN . "/medias/{$source}") ?>?format=select" role="button"><i class="la la-tasks"></i></a>
				</div>
			</div>

		</div>
	</div>