<?= $pager ? view('Adnduweb\Ci4Media\Views\pages') : '' ?>

<form name="files-form" method="post" action="<?= site_url(CI_AREA_ADMIN . '/medias/bulk') ?>">
    <?= $format === 'select' ? view('Adnduweb\Ci4Media\Views\Menus\bulk', ['access' => $access, 'bulks' => $bulks]) : '' ?>
    <?= view('Adnduweb\Ci4Media\Views\Formats\\' . $format, ['files' => $files, 'access' => $access, 'exports' => $exports]); ?>
</form>