	<?php $this->renderPartial('partial/photo_top_menu', array('type' => 'null'));?>	
	<div class="pad_left_10 pad_top_10">                  
		<div class="left list_mobile_request">
			<div class="photorequest-list">
			<?php if($return) {?>
				<?php foreach ($return as $key => $value) {?>
					<p class="date"><?php echo SysPhotoRequest::getDateTime($key);?></p>
					<ul class="photo-setting-request-list">
					<?php if(isset($value['data']['request'])) {?>
						<?php $this->renderPartial('partial/photo_setting/request', array('item' => $value['data']['request'], 'date' => $key));?>
					<?php }?>
					<?php if(isset($value['data']['response'])) {?>
						<?php $this->renderPartial('partial/photo_setting/response', array('item' => $value['data']['response'], 'date' => $key));?>
					<?php }?>
					</ul>
				<?php } ?>
			<?php } else {?>
				<p class="no-request-friends">
					<?php echo Lang::t('photo', 'No waiting photo request'); ?>
	            </p>
			<?php } ?>
			</div>
									<input type="hidden" name="photo_request_limit" id="photo_request_limit" value="<?php echo $limit; ?>" />
						<input type="hidden" name="photo_request_offset" id="photo_request_offset" value="<?php echo $limit; ?>" />
								<?php if($total >= $limit): ?> 
									<!-- more wrap -->	
				            		<div class="block_loading showmore show-more-searchresult show-more-photorequest">
				            			<a onclick="Photo.photorequest_showmore();" href="javascript:void(0);"><span></span></a>
				            		</div>
			            		<?php endif; ?>				
		</div>
		<?php $this->renderPartial('partial/photo_setting/popup', array());?>
	</div>
						<script type="text/javascript">
						$(document).ready(function(){
							Photo.photoUpdateReadStatus('<?php echo json_encode($response_id_json); ?>');
						});
					</script>