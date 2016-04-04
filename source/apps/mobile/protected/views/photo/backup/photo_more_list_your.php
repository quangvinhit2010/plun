<div class="profile-photo-more">
<?php 
$url = Yii::app()->createUrl('//my/view', array('alias' => $this->user->alias_name));
if($type==Photo::PUBLIC_PHOTO): 

?>                    	
<div class="block-photo-more">
    <h3><span class="public_photo"><?php echo Lang::t('photo', 'Public Photos'); ?></span></h3>
	<div class="back_link"><a href="<?php echo $url; ?>"><i class="imed imed-arrow-left"></i> <?php echo Lang::t('general', 'BACK'); ?></a></div>
    <div class="left list_pinterest">
        <div class="pint-style">
			<div class="list">	
				<?php foreach ($photos AS $photo): ?>										
				<div class="item hotbox-event">
					<?php echo $photo->getImageSmallThumbnail();?>
					<div class="mask">
						<a lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true); ?>" onclick="viewPhotoDetail(this);" title="" href="javascript:void(0);" class="ava">
						</a>
					</div>
				</div>	
				<?php endforeach; ?>
			</div>
			<div class="more-wrap show-more-searchresult" style="display: none;">
			    <button class="btn btn-white" onclick="showmore_searchresult();"><?php echo Lang::t('general', 'Show More'); ?></button>
			</div>
		</div>
    </div>
</div>      
<?php endif; ?>                                                  
<?php if($type==Photo::PRAVITE_PHOTO): ?>                  
 <div class="block-photo-more">
    <h3><span class="private_photo"><?php echo Lang::t('photo', 'Private Photos'); ?></span></h3>
	<div class="back_link"><a href="<?php echo $url; ?>"><i class="imed imed-arrow-left"></i> <?php echo Lang::t('general', 'BACK'); ?></a></div>
    <div class="left list_pinterest">
        <div class="pint-style">
			<div class="list">	
				<?php foreach ($photos AS $photo): ?>										
				<div class="item hotbox-event">
					<?php echo $photo->getImageSmallThumbnail();?>

		            
					<div class="mask">
					   <?php if($photo->request_status == null){ ?>
						<div class="ulti ulti-delete request-privatephoto<?php echo $photo->id; ?> active">
				             	<p></p>
				             	<!-- 
				             	<div class="buttons">
				              		<button class="btn btn-gray" onclick="request_privatePhoto('<?php echo $photo->id; ?>');">Send</button>
				             	 </div>
				             	  -->
			            </div>
		            <?php }else{
		            if($photo->request_status == SysPhotoRequest::REQUEST_PENDING){
		            ?>
                    	<div class="ulti ulti-delete request-privatephoto<?php echo $photo->id; ?> active">
			             	<p><?php echo Lang::t('photo', 'Request pending!'); ?></p>
			            </div>			
		            
		            <?php }
			            if($photo->request_status == SysPhotoRequest::REQUEST_DECLINED){
			            ?>				
			            <?php ?>	
                    	<div class="ulti ulti-delete request-privatephoto<?php echo $photo->id; ?> active">
			             	<p><?php echo Lang::t('photo', 'Request denied!'); ?></p>
			            </div>									            	            
			            <?php } 			            
		            } ?>
						<?php
						if($photo->request_status == SysPhotoRequest::REQUEST_ACCEPTED || $accept_all){
						?>
						<a lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true); ?>" onclick="viewPhotoDetail(this);" title="" href="javascript:void(0);" class="ava">			
						</a>
						<?php }else{ ?>													
						<a lcaption="<?php echo $photo->description;?>" title="" href="javascript:void(0);" class="ava" onclick="request_privatePhoto('<?php echo $photo->id; ?>');">
							<span class="bg-private icon-private-<?php echo $photo->id; ?>">
							<?php if($photo->request_status == null): ?>
							
                				<i></i>
                			
                			<?php endif; ?>
                			</span>
						</a>
						<?php } ?>
					</div>
					 
				</div>	
				<?php endforeach; ?>
			</div>
			<div class="more-wrap show-more-searchresult" style="display: none;">
			    <button class="btn btn-white" onclick="showmore_searchresult();"><?php echo Lang::t('general', 'Show More'); ?></button>
			</div>
		</div>
    </div>
</div>                            

<?php endif; ?>                             
</div>