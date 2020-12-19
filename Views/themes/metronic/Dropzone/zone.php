<div class="collapse" id="dropzoneCollapse">
    <?= form_open('', ['id' => 'kt_apps_manager_media', 'class' => 'kt-form m-10', 'novalidate' => false]); ?>
    <div class="dropzone dropzone-default kt_dropzone" data-acceptedFiles="null" data-maxFiles="10" data-uploadMultiple="true" data-crop="" data-field="" id="kt_dropzone_media_manager">
        <div class="dropzone-msg dz-message needsclick">
            <h3 class="dropzone-msg-title"><?= lang('Medias.Drop files here or click to upload'); ?>.</h3>
        </div>
    </div>
    <?= form_close(); ?>
</div>