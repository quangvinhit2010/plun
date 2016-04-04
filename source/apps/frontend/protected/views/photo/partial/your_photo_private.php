<div class="block-photo your-photo">
	<h3 class="private_photo"><?php echo Lang::t('photo', 'Private Photos'); ?></h3>
    <div class="list list_photo_user" id="private-photos">
    	<ul class="list-private-photos">
			<?php if($public_photos['pages']->getItemCount() > 0) { ?> 
				<!-- Begin Foreach -->
				<?php foreach ($private_photos['data'] AS $photo) { ?> 
					<?php if($photo->request_status == SysPhotoRequest::REQUEST_ACCEPTED || $accept_all){?>
						<li>
                        	<a lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);" title="" href="javascript:void(0);" class="ava">
                        	<?php echo $photo->getImageThumbnail();?>
		                    </a>
						</li>
					<?php }else{ ?>	
                    	<li class="pos_rel">
                        	<a title="" href="javascript:void(0);" class="ava" onclick="request_privatePhoto('<?php echo $photo->id; ?>');"><?php echo $photo->getImageThumbnail();?>
		                        <span class="bg-private icon-private-<?php echo $photo->id; ?>">
		                        <?php if($photo->request_status == null){ ?>
		                        	<i></i>
								<?php } ?>
		                        </span>
							</a>
						<?php if($photo->request_status == null){ ?>
	                    	<div class="ulti ulti-delete request-privatephoto<?php echo $photo->id; ?> active"><p></p>
			             	<!-- 
			             	<div class="buttons">
			              		<button class="btn btn-gray" onclick="request_privatePhoto('<?php echo $photo->id; ?>');">Send</button>
			             	</div>
			             	-->
							</div>
						<?php }else{ ?>
							<?php if($photo->request_status == SysPhotoRequest::REQUEST_PENDING){ ?>
			                	<div class="ulti ulti-delete request-privatephoto<?php echo $photo->id; ?> active">
									<p><?php echo Lang::t('photo', 'Request pending!'); ?></p>
								</div>			
							<?php } ?>
							<?php if($photo->request_status == SysPhotoRequest::REQUEST_DECLINED){ ?>				
		                    	<div class="ulti ulti-delete request-privatephoto<?php echo $photo->id; ?> active">
									<p><?php echo Lang::t('photo', 'Request denied!'); ?></p>
								</div>									            	            
							<?php } ?>
						<?php } ?>
	                    </li>
					<?php } ?>
                <?php } ?>
                <!-- End Foreach -->
                                       
				<?php if($public_photos['pages']->getItemCount() > $private_photos['pages']->pageSize) { ?>    
                	<li class="item">
						<a class="btn-more-load" href="javascript:Photo.show_more_your_photo('list-private-photos');">
							<label></label>
						</a>
					</li>  
				<?php } ?>                                        
		<?php } else { ?>
			<div class="left nodata">
		    	<p class="style_alert"><?php echo Lang::t("photo", '{username} doesn’t add any private photo', array('{username}'=>$user->getDisplayName()))?></p>
			</div>
		<?php } ?>
        </ul>
	</div>
</div>