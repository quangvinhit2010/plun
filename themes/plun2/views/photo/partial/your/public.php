<div class="photo-group photo_public" 
  data-type="<?php echo Photo::PUBLIC_PHOTO ?>"
  data-limit="<?php echo CParams::load ()->params->uploads->photo->limit_display->public_thumbnail ?>"
  data-offset="0">
	<div class="titlePhoto">
		<ins class="icon_common"></ins>
		<h4>
			<?php echo Lang::t('photo', 'Public Photos'); ?><span class="icon_common icon_discript"></span>
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
			<li class="item" id="<?php echo $photo->id ?>" data-type="<?php echo $photo->type ?>">
				<a href="<?php echo $photo->getImageLarge(true) ?>" class="group_gallery cboxElement">
					<img width="158" height="158" src="<?php echo $photo->getImageThumbnail(true) ?>" align="absmiddle">
				</a>
			</li>
			<?php endforeach; ?>
			<?php if(isset($photos['next_page']) && $photos['next_page'] != 'end') : ?>
			<li class="pagging"><a href="<?php echo $this->user->createUrl('//photo/listPhotos', array('type'=>Photo::PUBLIC_PHOTO, 'page'=>$photos['next_page'])) ?>"><ins></ins></a></li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>
	</div>
</div>