<?php $id = ($type == Photo::PUBLIC_PHOTO ) ? 'public_upload_photo' : 'private_upload_photo'; ?>
<?php $class = ($type == Photo::PUBLIC_PHOTO ) ? 'public_photo' : 'private_photo'; ?>
<div class="profile-photo-more">
<div class=" block-photo-more">
    <h3><span class="public_photo"><?php echo ($type == Photo::PUBLIC_PHOTO ) ? Lang::t('photo', 'Public Photos') : Lang::t('photo', 'Private Photos');?></span></h3>
	<div class="upload_link"><a class="upload_photo" href="javascript:void(0);" onclick="javascript:Photo.more_upload_photo_open(this, '<?php echo $id;?>');"><?php echo Lang::t('photo', 'Upload'); ?></a></div>
	<div class="back_link"><a href="<?php echo $this->usercurrent->createUrl('/photo/index')?>"><i class="imed imed-arrow-left"></i> <?php echo Lang::t('general', 'BACK'); ?></a></div>
	<div class="photos-upload">
					<?php
    				 	$this->widget('backend.extensions.EFineUploader.EFineUploader',
    						array(
    							'id'=>$id,
    							'config'=>array(
    								'multiple'=>true,
    								'autoUpload'=>true,
    				                'request'=>array(
    				                	'endpoint'=> $this->createUrl('photo/upload', array('type' => $type)),
    				                	'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
    								),
    								//'retry'=>array('enableAuto'=>true,'preventRetryResponseProperty'=>true),
    				                'chunking'=>array('enable'=>true,'partSize'=>100),//bytes
    				                'callbacks'=>array(
    				                	'onProgress'=>"js:function(id, name, loaded, total){ Photo.photoBeforeUpload(); }",
    				                	'onComplete'=>"js:function(id, name, response){ Photo.photoAfterUpload(response, 'photo-list-private'); }",
    				                    'onError'=>"js:function(id, name, errorReason){ Photo.photoOnError(errorReason); }",
    								),
    				                'validation'=>array(
    									'allowedExtensions'=>array('jpg','jpeg', 'png'),
    									'sizeLimit'=>20 * 1024 * 1024,//maximum file size in bytes
    									//'minSizeLimit'=>2*1024*1024,// minimum file size in bytes
    									//'itemLimit' => 1
    								),
    						)
    					));
    				?>
    	</div>			
		<!-- if no photo display -->
		<div id="photo-list-private" class="photos-list">
			<ul class="item-list">
			<ul>
		</div>
	
    <div class="left list_pinterest">
        <div class="pint-style">
			<div class="list">	
				<?php foreach ($photos AS $photo): ?>										
				<div class="item hotbox-event" id="<?php echo $photo->id; ?>" onmouseover="$(this).find('.ulti-done').toggle();" onmouseout="$(this).find('.ulti-done').toggle();">
					<?php echo $photo->getImageSmallThumbnail();?>
					<div class="mask">
						<a lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true); ?>" onclick="viewPhotoDetail(this);" title="" href="javascript:void(0);" class="ava">
						</a>
					</div>
					<div class="ulti ulti-done">
						<ul>
							<li><a class="btn-del" style="margin-left: 180px;" href="javascript:void(0);" onclick="javascript:Photo.delete_photo(this);"><i class="ismall ismall-x"></i></a></li>
						</ul>
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
</div>