<?php if(isset($item)) {?>
	<?php foreach ($item as $status) {?>
		<?php foreach ($status as $user_id =>  $photos) {?>
			<?php //$user = Member::model()->findByPK($photo['photo_user_id']); ?>
			<?php //$get_photo = Photo::model()->findByPk($photo['photo_id']); ?>
			<?php foreach ($photos['response_list'] as $key => $photo) {?>
			<li class="item response-photo-<?php echo $photo['id']; ?>">
				<div class="feed clearfix">
					<a href="<?php echo $photos['response_user_info']['url']; ?>" title="" class="ava">
						<?php echo $photos['response_user_info']['avatar']; ?>
					</a>
					<div class="info info_photo_setting">
						<p class="text">
							<a class="nickname" href="<?php echo $photos['response_user_info']['url']; ?>"><?php echo $photos['response_user_info']['name']; ?></a>
							<?php if($photo['status'] == SysPhotoRequest::REQUEST_ACCEPTED) { ?>
								<?php echo Lang::t('photo', 'accepted'); ?> 
								<a href="javascript:void(0);" lbutton="false" lid="<?php echo $photo['id']; ?>" lcaption="<?php echo $photo['description']; ?>" limg="<?php echo $photo['large_thumbnail_url']; ?>" onclick="Photo.viewPhotoDetail(this);">
									<?php echo Lang::t('photo', 'your photo request'); ?>
								</a>
							<?php } elseif ($photo['status'] == SysPhotoRequest::REQUEST_DECLINED) {?>
								<?php echo Lang::t('photo', 'declined'); ?> <?php echo Lang::t('photo', 'your photo request'); ?>
							<?php } ?>
						</p>
						<p class="time-detail"><?php echo Util::getElapsedTime($photo['date_request']) ?></p>
						<?php if($photo['status'] == SysPhotoRequest::REQUEST_ACCEPTED) { ?>
							<ul class="list_avatar">
			                	<li>
				                	<a href="javascript:void(0);" lbutton="false" lid="<?php echo $photo['id']; ?>" lcaption="<?php echo $photo['description']; ?>" limg="<?php echo $photo['large_thumbnail_url']; ?>" onclick="Photo.viewPhotoDetail(this);">
				                		<?php echo $photo['small_thumbnail_html']; ?>
				                	</a>
			                	</li>
							</ul>
						<?php } ?>
					</div>
				<!-- main info -->
				</div>
			</li>
			<?php } ?>
		<?php } ?>	
	<?php } ?>
<?php } ?>