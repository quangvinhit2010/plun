<div class="pad_left_10 pad_top_10">                  
  	<div class="left list_photo">
    	<div class="title_function">
        	<div class="left"><h4 class="mar_top_5 mag_bot_5"><?php echo Lang::t('register', 'Set avatar')?></h4></div>
        </div>
        <?php if($public_photos['pages']->getItemCount() > 0) { ?>
    	<ul class="list_photo_public">
    	    <?php foreach ($public_photos['data'] as $key => $photo) {?>
            <li <?php echo (($key % 3) == 2) ? 'class="end"' : '';?>>
                <a href="javascript:void(0);" data-photo-id="<?php echo $photo->id;?>" lis_me="true" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Avatar.viewPhotoDetail(this);">
					<?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle'));?>
				</a>
            </li>
            <?php } ?>
        </ul>
        <?php } else { ?>
		<div class="nodata">
        	<p class="style_alert"><?php echo Lang::t("photo", '{username} hasn’t added any public photo', array('{username}'=>'<b>'.$this->user->getDisplayName().'</b>'))?></p>
		</div>
	<?php } ?>
    </div>
</div>