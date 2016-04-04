
                      	<?php foreach ($vault_photos as $key => $photo): ?>

                      	<!-- it's me -->
                      	<?php if($is_me){ ?>
 	                        <li id="photo-<?php echo $photo->id; ?>"> 
	                        	<a href="javascript:void(0);" lis_me="true" data-photo-id="<?php echo $photo->id;?>" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewYourPhotoDetail(this);">
	                        		<?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle', 'width' => '95px', 'height' => '95px'));?>
	                        	</a>
	                        </li>
	                    <?php }else{ ?>                      	
                      	<!-- not me -->
		                      	<!-- if Accept -->	
		                      	<?php if($photo->getStatus() == SysPhotoRequest::REQUEST_ACCEPTED): ?>
			                        <li id="photo-<?php echo $photo->id; ?>"> 
			                        	<a href="javascript:void(0);" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewYourPhotoDetail(this);">
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
          