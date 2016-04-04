					   <div class="block news-feed photo-setting-request">
						<div class="title">
							<h2><?php echo Lang::t('photo', 'Photo Settings'); ?></h2>
						</div>
						<div class="cont cont_photo_setting">
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
										<!-- 
										<div class="show_more_small">
			                        		<a onclick="Photo.open_request_popup('request-photo-8-2014-06-16', this);" href="javascript:void(0);"><span></span></a>
			                        	</div>
			                        	 -->
									<?php } ?>
								<?php } else {?>
									<p class="no-request-friends">
										<?php echo Lang::t('photo', 'No waiting photo request'); ?>
						            </p>
								<?php } ?>

							</div>
								<?php if($total >= $limit): ?> 
									<!-- more wrap -->
				                    <div class="more-wrap-col2 show-more-photorequest">
				            			<a class="showmore" onclick="Photo.photorequest_showmore();" href="javascript:void(0);"><?php echo Lang::t('general', 'Share Photo'); ?></a>
				            		</div>		
			            		<?php endif; ?>		
			            					
						</div>
						<input type="hidden" name="photo_request_limit" id="photo_request_limit" value="<?php echo $limit; ?>" />
						<input type="hidden" name="photo_request_offset" id="photo_request_offset" value="<?php echo $limit; ?>" />
						<?php 
						$content =  $this->renderPartial('partial/photo_setting/popup', array(), true);
						$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'photo-view-request', 'content'=>$content));
						?>
						
						<!-- news feed list -->
						<!-- 
						<div class="more-wrap">
							<button class="btn btn-white">Show More</button>
						</div>
						-->
						
					</div>
					<script type="text/javascript">
						$(document).ready(function(){
							Photo.photoUpdateReadStatus('<?php echo json_encode($response_id_json); ?>');
						});
					</script>

