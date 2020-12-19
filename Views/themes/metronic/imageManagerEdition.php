<div class="modal modal-stick-to-bottom fade manager" id="kt_modal_manager_edition" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ImageManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ImageManagerModalLabel"><?= lang('Medias.Edition du fichier'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <?= form_open('', ['id' => 'kt_apps_manager_media', 'class' => 'kt-form', 'novalidate' => false]); ?>
            <div class="modal-body">
                <div class="flexbox-container">
                    <div class="flexbox-container-1">
                        <div class="attachment-details">
                        <?php if (!empty($media)) { ?>
                            <?php $file = new \CodeIgniter\Files\File($media->getPath('original')); ?>
                            <?php list($width, $height, $type, $attr) = getimagesize($media->getPath('original')); ?>
                            <div data-type="<?= $media->getType(); ?>" data-media-thumbnail="<?= img_data($media->getOriginal()) ?>" id="attachment-media-view_<?= $media->getIdMedia(); ?>" class="attachment-media-view <?= $media->getType(); ?> landscape attachment-media-view<?= $media->getIdMedia(); ?>" data-uuid-media="<?= $media->getUuid(); ?>">
                                <div class="thumbnail thumbnail-image" id="thumbnail-image<?= $media->getIdMedia(); ?>">
                                    <img class="details-image" src="<?= img_data($media->getOriginal()) ?>" />

                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <div id="cropImage"></div>
                    </div>
                    <div class="attachment-info flexbox-container-2">
                        <div class="information">
                            <div> <strong><?= lang('Medias.Nom du fichier'); ?></strong> : <?= $file->getBasename(); ?> </div>
                            <div> <strong><?= lang('Medias.Taille du fichier'); ?></strong> : <?= bytes2human($file->getSize()); ?></div>
                            <div> <strong><?= lang('Medias.Type du fichier'); ?></strong> : <?= $media->getType(); ?> </div>
                            <div> <strong><?= lang('Medias.Date du fichier'); ?></strong> : <?= $media->setCreatedAt($media->created_at); ?> </div>
                            <?php if ($media->getOrientation() != 'square') { ?>
                                <div> <strong><?= lang('Medias.dimensions du fichier'); ?></strong> : <?= $width; ?> pixels par <?= $height; ?> pixels </div>
                            <?php } ?>
                            <div> <strong><?= lang('Medias.url du fichier'); ?></strong> :  <?= $media->getUrlMedia(); ?> </div>
                            <hr>
                            <?php if (service('Settings')->setting_activer_multilangue == true) { ?>
                                <?php $setting_supportedLocales = json_decode(service('Settings')->setting_supportedLocales); ?> 
                                <div class="lang_tabs" data-dft-lang="<?= service('Settings')->setting_lang_iso; ?>" style="display: block;">

                                    <?php foreach ($setting_supportedLocales as $k => $v) {
                                        $langExplode = explode('|', $v); ?>
                                        <a href="javascript:;" data-lang="<?= $langExplode[1]; ?>" data-id_lang="<?= $langExplode[0]; ?>" class="btn <?= (service('Settings')->setting_id_lang == $langExplode[0]) ? 'btn-success active'  : 'btn-primary'; ?> lang_tab btn-icon btn-outline-brand"><?= ucfirst($langExplode[1]); ?></a>
                                    <?php   } ?>
                                </div>
                                <hr>
                            <?php   } ?>
                           
                            <div class="kt-portlet__body">
                                <div class="form-group">
                                    <label><?= lang('Core.titre'); ?></label>
                                    <?= form_input_spread('titre', $media->_prepareLang(), 'id="titre" class="form-control lang"', 'text', true); ?>
                                </div>
                                <div class="form-group">
                                    <label><?= lang('Core.legende'); ?></label>
                                    <?= form_input_spread('legende', $media->_prepareLang(), 'id="titre" class="form-control lang"', 'text', true); ?>
                                </div>
                                <div class="form-group">
                                    <label><?= lang('Core.description'); ?> (Alt) </label>
                                    <?= form_input_spread('description', $media->_prepareLang(), 'id="titre" class="form-control lang"', 'text', true); ?>
                                </div>
                                <?= form_input(['type'  => 'hidden', 'name'  => 'id_media', 'id'    => 'id_media', 'value' => $media->getIdMedia(), 'class' => 'id_media']); ?>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <button type="submit" class="btn btn-primary font-weight-bolder"><?= lang('Core.save'); ?></button>
                                    <button data-imagemanager="reload" data-uuid="<?= $media->getUuid(); ?>" type="button" class="btn btn-danger deleteFileMedia"><?= lang('Medias.supprimer file'); ?></button>
                                    <?php if (preg_match('/^image/',  $media->getType()) && $media->extension  != 'svg') { ?>
                                        <button data-crop="true" data-uuid="<?= $media->getUuid(); ?>" type="button" class="btn btn-dark croppedFile"><?= lang('Medias.cropped file'); ?></button>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr>
                            <div id="imageCustom">
                                <?= $this->include('Adnduweb\Ci4Media\Views\themes\metronic\imageCustom') ?>
                            </div>
                        </div>
                    </div> <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>