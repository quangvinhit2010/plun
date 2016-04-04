<div class="photo-group photo_vault" 
  data-type="<?php echo Photo::VAULT_PHOTO ?>"
  data-limit="<?php echo CParams::load ()->params->uploads->photo->limit_display->vault_thumbnail ?>"
  data-offset="0">
	<div class="titlePhoto">
		<ins class="icon_common"></ins>
		<h4>
			<?php echo Lang::t('photo', 'Vault Photos'); ?><span class="icon_common icon_discript"></span>
		</h4>
	</div>
	<div class="content">
		<?php if(empty($photos['data'])): ?>
		<div class="no_photo">
			<p><label></label></p>
			<p class="caution">No photo to show</p>
		</div>
		<?php else: ?>
		<ul>
			<?php foreach($photos['data'] as $photo): ?>
				<?php if($photo->isAccept()) : ?>
				<li class="item" id="<?php echo $photo->id ?>" data-type="<?php echo $photo->type ?>">
					<a href="<?php echo $photo->getImageLarge(true) ?>" class="group_gallery cboxElement">
						<img width="158" height="158" src="<?php echo $photo->getImageThumbnail(true) ?>" align="absmiddle">
					</a>
				</li>
				<?php else: ?>
					<?php if($photo->getStatus() == SysPhotoRequest::REQUEST_NONE): ?>
					<li class="item photo_vault bg_blur bg_soc">
						<img class="pics" src="<?php echo $photo->getImageThumbnail(true) ?>" align="absmiddle">
						<div class="mask_w"></div>
						<a title="Vault Photo" href="javascript:;" onclick="Photo.request_view_photo('<?php echo $photo->id; ?>', this);">
		                    <div class="wrap_icon_photo">
		                        <ins class="icon_vault"></ins>
		                        <div class="tooltip" style="display: none;">
		                            <p><?php echo Lang::t('photo', 'Send request');?></p>
		                            <label class="arrow"></label>
		                        </div>
		                    </div>
						</a>                        
					</li>
					<?php elseif($photo->getStatus() == SysPhotoRequest::REQUEST_PENDING) : ?>
					<li class="item photo_vault bg_blur bg_soc">
						<img class="pics" src="<?php echo $photo->getImageThumbnail(true) ?>" align="absmiddle">
						<div class="mask_w"></div>
						<a title="Vault Photo" href="javascript:;" class="active">
		                    <div class="wrap_icon_photo">
		                        <ins class="icon_vault"></ins>
		                    </div>
		                    <label><?php echo Lang::t('photo', 'Request sent!'); ?></label>
		                </a>
					</li>
					<?php else : ?>
					<li class="item photo_vault bg_blur bg_soc">
						<img class="pics" src="<?php echo $photo->getImageThumbnail(true) ?>" align="absmiddle">
						<div class="mask_w"></div>
						<a title="Vault Photo" href="javascript:;" onclick="Photo.requestAgain('<?php echo $photo->id; ?>', '<?php echo $this->user->id ?>', this);">
		                    <div class="wrap_icon_photo">
		                        <ins class="icon_vault"></ins>
		                        <div class="tooltip" style="display: none;">
		                            <p><?php echo Lang::t('photo', 'Resend Request');?></p>
		                            <label class="arrow"></label>
		                        </div>
		                    </div>
		                    <label><?php echo Lang::t('photo', 'Request denied!'); ?></label>
		                </a>
					</li>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if(isset($photos['next_page']) && $photos['next_page'] != 'end') : ?>
			<li class="pagging"><a href="<?php echo $this->user->createUrl('//photo/listPhotos', array('type'=>Photo::VAULT_PHOTO, 'page'=>$photos['next_page'])) ?>"><ins></ins></a></li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>
	</div>
</div>