<!-- public -->
<div class="block-photo your-photo">
	<h3 class="public_photo"><?php echo Lang::t('photo', 'Public Photos'); ?></h3>
    <div class="list list_photo_user" id="public-photos">
	<?php if($public_photos['pages']->getItemCount() > 0) { ?>
    	<ul class="list-public-photos" page="<?php echo $public_photos['next_page'];?>" type="<?php echo $public_photos['type'];?>" url="<?php echo $this->user->createUrl('//photo/listPhoto');?>">
        	<?php foreach ($public_photos['data'] AS $photo) { ?>
	            <li>
	            	<a lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true); ?>" onclick="Photo.viewPhotoDetail(this);" title="" href="javascript:void(0);" class="ava">
	                <?php echo $photo->getImageThumbnail();?>
		            </a>
				</li>
           <?php } ?>
           <?php if($public_photos['pages']->getItemCount() > $public_photos['pages']->pageSize ){ ?>    
           		<li class="item">
					<a class="btn-more-load" href="javascript:Photo.show_more_your_photo('list-public-photos');">
						<label></label>
					</a>
				</li>  
			<?php } ?>                            
		</ul>      
	<?php } else { ?>
		<div class="left nodata">
        	<p class="style_alert"><?php echo Lang::t("photo", '{username} hasn’t added any public photo', array('{username}'=>$user->getDisplayName()))?></p>
		</div>
	<?php } ?>                             
	</div>
</div>