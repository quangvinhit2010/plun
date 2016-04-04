<?php if(isset($item)) {?>
	<?php foreach ($item as $status) {?>
		<?php foreach ($status as $user_id =>  $photos) {?>
			<?php 
				$total_photos = count($photos['response_list']) ;
				$user_photo_id = $user_id.'-'.$date;
			?>
			<li class="item request-photo-<?php echo $user_photo_id; ?>">
				<div class="feed clearfix">
					<a href="<?php echo $photos['response_user_info']['url']; ?>" title="" class="ava">
						<?php echo $photos['response_user_info']['avatar']; ?>
					</a>
					
					<div class="info info_photo_setting">
							<p>
								<span class="username"><a href="<?php echo $photos['response_user_info']['url']; ?>"><?php echo $photos['response_user_info']['name']; ?></a></span>
								<?php foreach ($photos['response_list'] as $key => $photo) {?>
									<?php
										//$response = SysPhotoRequest::model()->findByPk($photo['id']);
										//$response->is_read = 3;
										//$response->update(); 
									?>
									<?php //$get_photo = Photo::model()->findByPk($photo['photo_id']); ?>
									<?php if($photo['status'] == SysPhotoRequest::REQUEST_ACCEPTED && $key == 0) { ?>
										<?php echo Lang::t('photo', 'accepted'); ?> 
											<?php if($total_photos > 1) { ?>
												<?php echo $total_photos;?> <?php echo Lang::t('photo', 'your photo request'); ?>
											<?php } else { ?>
												<?php echo Lang::t('photo', 'your photo request'); ?>
											<?php } ?>
												
									<?php } elseif ($photo['status'] == SysPhotoRequest::REQUEST_DECLINED && $key == 0) {?>
										<?php if($total_photos > 1) { ?>
											<?php echo Lang::t('photo', 'declined'); ?> <?php echo $total_photos;?> <?php echo Lang::t('photo', 'your photo request'); ?>
										<?php } else { ?>
											<?php echo Lang::t('photo', 'declined'); ?> <?php echo Lang::t('photo', 'your photo request'); ?>
										<?php } ?>
									<?php } ?>
								
								<?php } ?>
							</p>
							
							
							<?php if(isset($photos['response_list'][0])): ?>
								<p class="subtime"><?php echo Util::getElapsedTime($photos['response_list'][0]['date_request']); ?></p>
							<?php endif; ?>							
							
							<ul class="list_avatar">
								<?php foreach ($photos['response_list'] as $key => $photo) {?>
									<?php if($photo['status'] == SysPhotoRequest::REQUEST_ACCEPTED) { ?>
						                	<li>
							                	<a href="javascript:void(0);" lbutton="false" lid="<?php echo $photo['id']; ?>" lcaption="<?php echo $photo['description']; ?>" limg="<?php echo $photo['large_thumbnail_url']; ?>" onclick="Photo.viewPhotoDetail(this);">
							                		<?php echo $photo['thumbnail_html']; ?>
							                	</a>
						                	</li>
									<?php } ?>
								<?php } ?>
							</ul>
							
					</div>
				</div>
			</li>
		<?php } ?>
	<?php } ?>
<?php } ?>
		
		
		
		
		