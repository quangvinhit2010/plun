<div class="clearfix sticky_column wrap_photos_right">
	<div class="photo_setting left">
		<div class="title">
			<h3 class="left"><?php echo Lang::t('photo', 'Photo Settings'); ?></h3>
		</div>
		<div class="content">
			
				<?php if($return) : ?>
					<div class="request-content-wrap">
					<?php foreach($return as $key => $value): ?>
						<p class="date"><?php echo SysPhotoRequest::getDateTime($key) ?></p>
						<ul class="photo-setting-request-list">
							<?php if(isset($value['data']['request'])): ?>
								<?php $this->renderPartial('partial/photo_setting/request', array('item' => $value['data']['request'], 'date' => $key));?>
							<?php endif; ?>
							<?php if(isset($value['data']['response'])): ?>
								<?php $this->renderPartial('partial/photo_setting/response', array('item' => $value['data']['response'], 'date' => $key));?>
							<?php endif; ?>
						</ul>
					<?php endforeach; ?>
					</div>
					<?php if($total >= $limit): ?> 
					<div class="pagging">
						<a onclick="Photo.photorequest_showmore();" href="javascript:;"><ins></ins></a>
						<input type="hidden" name="photo_request_limit" id="photo_request_limit" value="<?php echo $limit; ?>" />
						<input type="hidden" name="photo_request_offset" id="photo_request_offset" value="<?php echo $limit; ?>" />
					</div>	
					<?php endif; ?>
			            		
				<?php else : ?>
					<div class="no_photo" style="width: 336px; margin-top: 20px;">
						<p><label></label></p>
						<p class="caution"><?php echo Lang::t('photo', 'No waiting photo request'); ?></p>
					</div>
				<?php endif; ?>

		</div>
		<div style="display: none;">
			<div class="popup_genneral popup_request_photo">
				<div class="content">
					<div class="scroll" style="height: 370px; overflow: hidden;">
						<ul style="height: auto">
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
					<script type="text/javascript">
						$(document).ready(function(){
							Photo.photoUpdateReadStatus('<?php echo json_encode($response_id_json); ?>');
						});
					</script>