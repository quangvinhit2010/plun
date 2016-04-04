<div class="photo-group photo_private" 
  data-type="<?php echo Photo::PRAVITE_PHOTO ?>"
  data-limit="<?php echo CParams::load ()->params->uploads->photo->limit_display->private_thumbnail ?>"
  data-offset="0">
	<div class="titlePhoto">
		<ins class="icon_common"></ins>
		<h4>
			<?php echo Lang::t('photo', 'Private Photos'); ?><span class="icon_common icon_discript"></span>
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
					<a href="<?php echo $this->createUrl('Detail', array('id'=>$photo->id)) ?>" class="group_gallery">
						<img width="158" height="158" src="<?php echo $this->createUrl('Thumbnail', array('id'=>$photo->id)) ?>" align="absmiddle" width="158" height="158">
					</a>
				</li>
				<?php else: ?>
				<li class="item photo_private bg_blur bg_soc">
					<img class="pics" src="<?php echo $this->createUrl('WaterMarkThumbnail', array('id'=>$photo->id)) ?>" align="absmiddle">
					<a title="<?php echo Lang::t('photo', 'Private Photos'); ?>" href="javascript:;" onclick="Photo.request_view_private_photo('<?php echo $photo->id; ?>', this);">                        	
						<div class="wrap_icon_photo">
	                        <ins class="icon_private"></ins>
	                        <div class="tooltip" style="display: none;">
	                            <p><?php echo Lang::t('photo', 'Pay {candy} candies to view photo', array('{candy}'=>'<span style="color: rgb(255, 255, 0)">'.$photo->photo_private->getPrice().'</span>'));?></p>
	                            <label class="arrow"></label>
	                        </div>
	                    </div>
					</a>                        
				</li>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if(isset($photos['next_page']) && $photos['next_page'] != 'end') : ?>
			<li class="pagging"><a href="<?php echo $this->user->createUrl('//photo/listPhotos', array('type'=>Photo::PRAVITE_PHOTO, 'page'=>$photos['next_page'])) ?>"><ins></ins></a></li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>
	</div>
</div>