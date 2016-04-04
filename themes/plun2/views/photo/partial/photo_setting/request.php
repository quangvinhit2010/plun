<?php if(isset($item)) {?>
	<?php foreach ($item as $key => $photo) {
				$user_photo_id = $key.'-'.$date;
			?>
			<li class="item" id="view-request-photo-<?php echo $user_photo_id; ?>">
				<div class="feed clearfix">
					<a class="ava" title="" href="<?php echo $photo['request_user_info']['url'] ?>">
						<?php echo $photo['request_user_info']['avatar']; ?>
					</a>
					<div class="info info_photo_setting">
						<p>
							<span class="username">
								<a href="<?php echo $photo['request_user_info']['url'] ?>"><?php echo $photo['request_user_info']['name'] ?></a>
							</span>
							<?php echo Lang::t('photo', 'asked to') ?>
							<a onclick="Photo.open_request_popup('request-photo-<?php echo $user_photo_id; ?>', this, 'view-request-photo-<?php echo $user_photo_id; ?>');" href="javascript:void(0);">
								<?php echo Lang::t('photo', 'view photo'); ?>
							</a>
						</p>
						<?php if(isset($photo['request_list'][0])): ?>
						<p class="subtime"><?php echo Util::getElapsedTime($photo['request_list'][0]['date_request']); ?></p>
						<?php endif; ?>
						<ul class="list_avatar">
							<?php if(isset($photo['request_list'][0])): ?>
								<?php $is_more = 0;?>
								<?php foreach ($photo['request_list'] as  $group_photo) {?>
									<?php if($group_photo['photo_user_id'] == Yii::app()->user->id) {?>
										<?php $is_more++;?>
										<?php if($is_more < 8) {?>
										<li id="list-request-photo-<?php echo $group_photo['id'];?>"><a href="javascript:void(0);" onclick="Photo.open_request_popup('request-photo-<?php echo $user_photo_id; ?>', this, 'view-request-photo-<?php echo $user_photo_id; ?>');"><?php echo $group_photo['thumbnail_html']; ?></a></li>
										<?php } else { ?>
										<li style="display: none;" id="list-request-photo-<?php echo $group_photo['id'];?>"><a href="javascript:void(0);" onclick="Photo.open_request_popup('request-photo-<?php echo $user_photo_id; ?>', this, 'view-request-photo-<?php echo $user_photo_id; ?>');"><?php echo $group_photo['thumbnail_html']; ?></a></li>
										<?php } ?>
									<?php } ?>	
								<?php } ?>
								<?php if($is_more > 8) {?>
									<li class="end_more"><a href="javascript:void(0);" onclick="Photo.open_request_popup('request-photo-<?php echo $user_photo_id; ?>', this, 'view-request-photo-<?php echo $user_photo_id; ?>');"><span></span></a></li>
								<?php }?>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</li>
		<?php } ?>	
<?php } ?>


