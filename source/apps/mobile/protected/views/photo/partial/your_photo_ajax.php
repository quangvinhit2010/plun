<?php if($photos['pages']->getItemCount() > 0) { ?>
	<?php foreach ($photos['data'] AS $photo) { ?>
		<?php if($photo['type'] != Photo::PUBLIC_PHOTO) {?>
			<?php if($photo->isAccept() || $photo->isAcceptAll()){?>
				<li>
                	<a lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);" title="" href="javascript:void(0);" class="ava">
                    	<?php echo $photo->getImageThumbnail();?>
					</a>
				</li>
			<?php }else{ ?>	
                <li class="pos_rel" id="photo-<?php echo $photo->id; ?>">
                    <a title="" href="javascript:void(0);" class="ava" onclick="Photo.request_view_photo('<?php echo $photo->id; ?>');">
	                    <?php echo $photo->getImageThumbnail();?>
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
		<?php } else { ?>
	    	<li>
	        	<a lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true); ?>" onclick="Photo.viewPhotoDetail(this);" title="" href="javascript:void(0);" class="ava">
	            	<?php echo $photo->getImageThumbnail();?>
				</a>
			</li>
		<?php } ?>
	<?php } ?>
<?php } ?>                             
<div style="display: none;" id="next_page" page="<?php echo $photos['next_page'];?>"></div>