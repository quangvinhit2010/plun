<div class="block-photo your-photo">
	<h3 class="vault_photo"><?php echo Lang::t('photo', 'Vault Photos'); ?></h3>
    <div class="list list_photo_user" id="vault_photos">
    	<ul class="list-private-photos" page="<?php echo $vault_photos['next_page'];?>" type="<?php echo $vault_photos['type'];?>" url="<?php echo $this->user->createUrl('//photo/listPhoto');?>">
			<?php if($vault_photos['pages']->getItemCount() > 0) { ?> 
				<!-- Begin Foreach -->
				<?php foreach ($vault_photos['data'] AS $photo) { ?>
					<?php if($photo->isAccept()){?>
						<li>
                        	<a lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageMedium(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);" title="" href="javascript:void(0);" class="ava">
                        	<?php echo $photo->getImageThumbnail();?>
		                    </a>
						</li>
					<?php }else{ ?>	
                    	<li class="pos_rel" id="photo-<?php echo $photo->id; ?>">
                        	<a title="" href="javascript:void(0);" class="ava" onclick="Photo.request_view_photo('<?php echo $photo->id; ?>');"><?php echo $photo->getImageThumbnail();?>
		                        <span class="bg-private icon-private-<?php echo $photo->id; ?>">
		                        <?php if($photo->getStatus() == false){ ?>
		                        	<i></i>
								<?php } ?>
		                        </span>
							</a>
	                    	<?php if($photo->getStatus() == SysPhotoRequest::REQUEST_PENDING){ ?>
		                    	<div class="ulti ulti-delete request-privatephoto<?php echo $photo->id; ?> active">
	                    		<p><?php echo Lang::t('photo', 'Request sent!'); ?></p>
	                    		</div>
	                    	<?php }?>
	                    	<?php if($photo->getStatus() == SysPhotoRequest::REQUEST_DECLINED){ ?>
		                    	<div class="ulti ulti-delete request-privatephoto<?php echo $photo->id; ?> active">
	                    		<p><?php echo Lang::t('photo', 'Request denied!'); ?></p>
	                    		</div>
	                    	<?php }?>
	                    </li>
					<?php } ?>
                <?php } ?>
                <!-- End Foreach -->
                                       
				<?php if($vault_photos['pages']->getItemCount() >  $vault_photos['pages']->pageSize) { ?>    
                	<li class="item">
						<a class="btn-more-load" href="javascript:Photo.show_more_your_photo('list-private-photos');">
							<label></label>
						</a>
					</li>  
				<?php } ?>                                        
		<?php } else { ?>
			<div class="left nodata">
		    	<p class="style_alert"><?php echo Lang::t("photo", '{username} hasn’t added any vault photo', array('{username}'=>$user->getDisplayName()))?></p>
			</div>
		<?php } ?>
        </ul>
	</div>
</div>