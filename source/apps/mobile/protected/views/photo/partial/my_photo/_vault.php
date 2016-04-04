<div class="title_function">
	<div class="left"><h4><?php echo Lang::t('photo', 'Vault Photos'); ?></h4></div>
	<div class="right"><a href="javascript:Photo.upload_photo_open(this, 'vault_upload_photo');">+ <?php echo Lang::t('photo', 'Add more'); ?> </a></div>
</div>
<ul id="photo-list-vault" class="list_photo_public" page="<?php echo $vault_photos['next_page'];?>" type="<?php echo $vault_photos['type'];?>">
	<?php if($vault_photos['pages']->getItemCount() > 0) {?>
		<?php foreach ($vault_photos['data'] as $key => $photo) {?>
			<li id="<?php echo $photo->id;?>" <?php echo (($key % 3) == 2) ? 'class="end"' : '';?> >
				<a href="javascript:void(0);" lis_me="true" share_photo="true" lcaption="<?php echo $photo->description;?>" data-photo-id="<?php echo $photo->id;?>" limg="<?php echo $photo->getImageLarge(true); ?>" onclick="Photo.viewPhotoDetail(this);">
					<?php echo $photo->getImageThumbnail(false, array('align' => 'absmiddle'));?>
				</a>
			</li>
		<?php } ?>
		
		<?php if($vault_photos['pages']->getItemCount() > $vault_photos['pages']->pageSize ){ ?>    
			<div class="block_loading"><a href="javascript:Photo.show_more('photo-list-vault');"><span></span></a></div>
		<?php } ?>
	
	<?php } ?>
</ul>

<div id="popup-alert" class="photo-list-vault-dialog" style="display:none;">
					<?php	
    				 	$this->widget('backend.extensions.EFineUploader.EFineUploader',
    						array(
    							'id'=>'vault_upload_photo',
    							'config'=>array(
    								'multiple'=>false,
    								'autoUpload'=>true,
    				                'request'=>array(
    				                	'endpoint'=> $this->createUrl('photo/upload', array('type' => Photo::VAULT_PHOTO)),
    				                	'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
    								),
    								//'retry'=>array('enableAuto'=>true,'preventRetryResponseProperty'=>true),
    				                //'chunking'=>array('enable'=>true,'partSize'=>100),//bytes
    				                'callbacks'=>array(
    				                	'onProgress'=>"js:function(id, name, loaded, total){ Photo.photoBeforeUpload(); }",
    				                	'onComplete'=>"js:function(id, name, response){ Photo.photoAfterUpload(response, 'photo-list-vault'); }",
    				                    'onError'=>"js:function(id, name, errorReason){ Photo.photoOnError(errorReason); }",
    								),
    				                'validation'=>array(
    									'allowedExtensions'=>array('jpg','jpeg', 'png'),
    									'sizeLimit'=>20 * 1024 * 1024,//maximum file size in bytes
    									//'minSizeLimit'=>2*1024*1024,// minimum file size in bytes
    									'itemLimit' => 1
    								),
    						)
    					));
    				?>
</div>		    				