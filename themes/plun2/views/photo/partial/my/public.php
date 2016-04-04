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
		<div class="empty_photo_list"<?php if(!empty($photos['data'])) echo ' style="display: none;"' ?>>
			<p><?php echo Lang::t('photo', 'Public Photos are the photos which are accessible to other users, the maximum number for your Public Photos is {limit}.', array('{limit}'=>Yii::app()->params['uploads']['photo']['limit_upload']['public']))?></p>
			<a class="btnUploadPhoto" title="" href="javascript:;" onclick="javascript:Photo.upload_photo_open(this);">Upload photo</a>
		</div>
		<ul<?php if(empty($photos['data'])) echo ' style="display: none;"' ?>>
			<li class="add_photo">
				<a title="" href="javascript:;" onclick="javascript:Photo.upload_photo_open(this);">
					<ins></ins>
					<p><?php echo Lang::t('photo', 'Click to add more photos'); ?></p>
				</a>
			</li>
			<?php foreach($photos['data'] as $photo): ?>
			<li class="item" id="<?php echo $photo->id ?>" data-type="<?php echo $photo->type ?>">
				<a href="<?php echo $photo->getImageLarge(true) ?>" class="group_gallery cboxElement canSetMain">
					<img width="158" height="158" src="<?php echo $photo->getImageThumbnail(true) ?>" align="absmiddle">
				</a>
				<div class="share_del" style="display: none;">
					<ol>
						<li>
							<a href="javascript:;" class="delete_p" onclick="javascript:Photo.delete_photo(this);"><ins></ins><?php echo Lang::t('photo', 'Delete'); ?></a>
							<div class="tooltip" style="display: none;">
								<p><?php echo Lang::t('photo', 'Delete'); ?></p>
								<label class="arrow"></label>
							</div>
						</li>
					</ol>
				</div>
			</li>
			<?php endforeach; ?>
			<?php if(isset($photos['next_page']) && $photos['next_page'] != 'end') : ?>
			<li class="pagging"><a href="<?php echo Yii::app()->user->data()->createUrl('//photo/listPhotos', array('type'=>Photo::PUBLIC_PHOTO, 'page'=>$photos['next_page'])) ?>"><ins></ins></a></li>
			<?php endif; ?>
		</ul>
	</div>
</div>