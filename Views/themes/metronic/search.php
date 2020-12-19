<?php use \Adnduweb\Ci4Admin\Libraries\Theme; helper('time') ?>
<?php if(!empty($items)): ?>
<div class="font-size-sm text-primary font-weight-bolder text-uppercase mb-2">
        <?= lang('Medias.title_search'); ?>
    </div>
<div class="mb-10">
    <?php foreach($items as $item){ ?>
       
        <div class="d-flex align-items-center flex-grow-1 mb-2">
            <div class="symbol symbol-30 bg-transparent flex-shrink-0">
                <?php if($item->getOrientation() == 'square'){ ?>
                    <?= Theme::getSVG('assets/media/svg/files/doc.svg', 'svg-icon svg-icon-xl', true); ?> 
                <?php } ?>
                <?php if($item->getOrientation() != 'square'){ ?>
                    <?= Theme::getSVG('assets/media/svg/icons/Home/Picture.svg', 'svg-icon svg-icon-xl', true); ?> 
                <?php } ?>
            </div>
            <div class="d-flex flex-column ml-3 mt-2 mb-2">
                <a href="<?= base_url(CI_AREA_ADMIN . "/medias?uuid=" . $item->getUuid()); ?>" class="font-weight-bold text-dark text-hover-primary">
                    <?= (!$item->getBTitle()) ? lang('Core.sans_titre') : $item->getBTitle(); ?>
                </a>
                <span class="font-size-sm font-weight-bold text-muted">
                   <?= lang('Core.added'); ?> : <?= timeAgo($item->created_at); ?>  <?= lang('Core.by'); ?>  <?= $item->users[0]->getFullName(); ?>
                </span>
            </div>
        </div>
    <?php } ?>
    </div>
<?php endif; ?>