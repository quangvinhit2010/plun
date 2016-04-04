<?php if($return > 0) {?>
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
								<script type="text/javascript">
									Photo.photoUpdateReadStatus('<?php echo json_encode($response_id_json); ?>');
								</script>
							<?php } ?>