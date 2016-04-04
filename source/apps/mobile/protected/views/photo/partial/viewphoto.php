                <div class="friend_request">
                  <ul>
                    <li class="mar_rig_10"><a href="<?php echo $user->createUrl('//my/view');?>"><?php echo Lang::t('general', 'Profile'); ?></a></li>
                    <li class="active"><a href="<?php echo $user->createUrl('//photo/viewphoto');?>"><?php echo Lang::t('general', 'Photos'); ?></a></li>
                  </ul>
                </div>
                <div class="pad_left_10 userphoto">
                  <div class="public_photo_user">
                    <h4><span></span> <?php echo Lang::t('photo', 'Public Photos'); ?></h4>
                    <?php if(sizeof($public_photos['data'])){ ?>
                    <div class="left">
                      <ul>
                      	<?php foreach ($public_photos['data'] AS $photo): ?>
                        <li>
                        	<a href="javascript:void(0);" lis_me="true" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewYourPhotoDetail(this);">
                        		<?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle', 'width' => '95px', 'height' => '95px'));?>
                        	</a>
                        </li>
                        
						<?php endforeach; ?>
                      </ul>
                    </div>
                    <?php }else{ ?>
                    <div class="left nodata">
			        	<p class="style_alert"><?php echo Lang::t("photo", '{username} hasn’t added any public photo', array('{username}'=>$user->getDisplayName()))?></p>
					</div>
					<?php } ?>
                  </div>
                  <div class="vault_photo_user">
                    <h4><span></span> <?php echo Lang::t('photo', 'Vault Photos'); ?></h4>
                    <?php if(sizeof($vault_photos)){ ?>
                    <div class="left">
                      <ul>
                      	<?php foreach ($vault_photos as $key => $photo): ?>
                      	
                      	<!-- it's me -->
                      	<?php if($is_me){ ?>
 	                        <li id="photo-<?php echo $photo->id; ?>"> 
	                        	<a href="javascript:void(0);" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageMedium(true) . '?t=' . time(); ?>" onclick="Photo.viewYourPhotoDetail(this);">
	                        		<?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle', 'width' => '95px', 'height' => '95px'));?>
	                        	</a>
	                        </li>
	                    <?php }else{ ?>     
	                    <!-- not me -->                	
		                      	<!-- if Accept -->	
		                      	<?php if($photo->getStatus() == SysPhotoRequest::REQUEST_ACCEPTED): ?>
			                        <li id="photo-<?php echo $photo->id; ?>"> 
			                        	<a href="javascript:void(0);" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageMedium(true) . '?t=' . time(); ?>" onclick="Photo.viewYourPhotoDetail(this);">
			                        		<?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle', 'width' => '95px', 'height' => '95px'));?>
			                        	</a>
			                        </li>                      	
		                      	<?php endif; ?>
		                      	
		                       <!--  none request -->
		                      	<?php if($photo->getStatus() == SysPhotoRequest::REQUEST_NONE): ?>
		                        <li id="photo-<?php echo $photo->id; ?>"> 
		                        	<a href="javascript:void(0);" lcaption="<?php echo $photo->description;?>">
		                        		<?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle', 'width' => '95px', 'height' => '95px'));?>
		                        	</a>
		                          <div class="mask"><a onclick="Photo.request_view_photo('<?php echo $photo->id; ?>');" href="javascript:void(0);"></a></div>
		                        </li>
		                        <?php endif ?>
		                        
		                        <!-- did request -->
		                        <?php if($photo->getStatus() == SysPhotoRequest::REQUEST_PENDING): ?>
		                        	<li id="photo-<?php echo $photo->id; ?>">
		                            	<a href="javascript:void(0);"><?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle', 'width' => '95px', 'height' => '95px'));?></a>
		                                <div class="alert"><?php echo Lang::t('photo', 'Request sent!'); ?></div>
		                         	</li>                        
		                         <?php endif ?>
		                         
		                         <!-- did declined -->
		                         <?php if($photo->getStatus() == SysPhotoRequest::REQUEST_DECLINED): ?>
		                         	<li id="photo-<?php echo $photo->id; ?>">
		                            	<a href="javascript:void(0);"><?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle', 'width' => '95px', 'height' => '95px'));?></a>
		                                <div class="alert"><?php echo Lang::t('photo', 'Request denied!'); ?></div>
		                                <a class="request_again request_again_<?php echo $photo->id; ?>" href="javascript:void(0);" onclick="Photo.requestAgain('<?php echo $photo->id; ?>', '<?php echo $user->id ?>');"><?php echo Lang::t('photo', 'Resend Request');?></a>
		                         	</li>                        
		                         <?php endif ?>
                         <?php } ?>
                        <?php endforeach; ?>
                      </ul>                      
                    </div>
                    <?php }else{ ?>
                    <div class="left nodata"><?php echo Lang::t("photo", '{username} hasn’t added any vault photo', array('{username}'=>$user->getDisplayName()))?></div>
                    <?php } ?>
                  </div>
                  <?php if($total_vault_photo > $limit_private_thumbnail): ?>
                  <div class="block_loading"><a href="javascript:void(0);" onclick="Photo.viewmorephoto('<?php echo $user->alias_name ?>');"><span></span></a></div>
                  <?php endif; ?>
                  <input type="hidden" name="vault_photo_showmore_offset" id="vault_photo_showmore_offset" value="<?php echo $limit_private_thumbnail; ?>" />
                  <input type="hidden" name="vault_photo_showmore_limit" id="vault_photo_showmore_limit" value="<?php echo $limit_private_thumbnail; ?>" />
                  
                </div>                