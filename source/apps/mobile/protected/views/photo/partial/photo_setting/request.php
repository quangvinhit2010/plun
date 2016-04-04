<?php if(isset($item)) {?>
			<?php 
				foreach ($item as $key => $photo) {
					$user_photo_id = $key.'-'.$date;
			?>
			<li class="item request-photo-<?php echo $user_photo_id; ?>">
				<div class="feed clearfix">
					<a href="<?php echo $photo['request_user_info']['url']; ?>" title="" class="ava">
						<?php echo $photo['request_user_info']['avatar']; ?>
					</a>
					<div class="info info_photo_setting">
						<p class="text">
							<a class="nickname" href="<?php echo $photo['request_user_info']['url']; ?>"><b><?php echo $photo['request_user_info']['name']; ?></b></a>
							<?php echo Lang::t('photo', 'asked to'); ?> 
							<a onclick="Photo.open_request_popup('request-photo-<?php echo $user_photo_id; ?>', this);" href="javascript:void(0);">
								<?php echo Lang::t('photo', 'view photo'); ?>
							</a>
						</p>
						
						<?php if(isset($photo['request_list'][0])): ?>
							<p class="time-detail"><?php echo Util::getElapsedTime($photo['request_list'][0]['date_request']); ?></p>
						<?php endif; ?>
						
						<div class="list_thumb_photo">
							<ul class="photo_request_<?php echo $key;?> list_thumb_photo">
								<?php if(isset($photo['request_list'][0])): ?>
									<?php $is_more = 0;?>
									<?php foreach ($photo['request_list'] as  $group_photo) {?>
										<?php //$get_photo = Photo::model()->findByPk($group_photo['photo_id']);?>
										<?php if($group_photo['photo_user_id'] == Yii::app()->user->id) {?>
											<?php $is_more++;?>
				                			<?php if($is_more < 8) {?>
					                			<li class="list-request-photo-<?php echo $group_photo['id'];?>"><a href="javascript:void(0);" onclick="Photo.open_request_popup('request-photo-<?php echo $user_photo_id; ?>', this);"><?php echo $group_photo['thumbnail_html']; ?></a></li>
											<?php } else { ?>
												<li style="display: none;"class="list-request-photo-<?php echo $group_photo['id'];?>"><a href="javascript:void(0);" onclick="Photo.open_request_popup('request-photo-<?php echo $user_photo_id; ?>', this);"><?php echo $group_photo['thumbnail_html']; ?></a></li>
											<?php } ?>
				                		<?php } ?>	
				                	<?php } ?>
				                	<?php if($is_more > 8) {?>
				                		<li class="more"><a href="javascript:void(0);" onclick="Photo.open_request_popup('request-photo-<?php echo $user_photo_id; ?>', this);"><span></span></a></li>
				                	<?php }?>
				                <?php endif; ?>
							</ul>
						</div>
					</div>
				<!-- main info -->
				</div>
			</li>
		<?php } ?>	
<?php } ?>


