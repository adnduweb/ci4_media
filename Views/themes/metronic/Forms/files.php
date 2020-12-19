<?= $pager ? view('Adnduweb\Ci4Media\Views\themes\metronic\pages') : '' ?>

<form name="files-form" method="post" action="<?= site_url(CI_AREA_ADMIN . '/medias/bulk') ?>">
    <?= $format === 'select' ? view('Adnduweb\Ci4Media\Views\themes\metronic\Menus\bulk', ['access' => $access, 'bulks' => $bulks]) : '' ?>
    <?= view('Adnduweb\Ci4Media\Views\themes\metronic\Formats\\' . $format, ['files' => $files, 'access' => $access, 'exports' => $exports]); ?>
</form>