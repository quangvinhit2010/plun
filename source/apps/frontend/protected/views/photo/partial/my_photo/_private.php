		<!-- Photos Upload Single Block: Private Photos Area -->
		<div class="photos-block photos-private">
			<div class="tabs clearfix">
				<ul>
					<li rel="photo-list-private" class="private_photo <?php echo ($private_photos['data']) ? 'active' : '';?>">
						<a href="javascript:void(0);" data-toggle="tab" data-target="#photos-private-list"><?php echo Lang::t('photo', 'Private Photos'); ?></a>
						<span class="line"></span>
					</li>
					<li>
						<span class="sep"></span>
					</li>
					<li class="upload_photo <?php echo (empty($private_photos['data'])) ? 'active' : '';?>">
						<a href="javascript:void(0);" data-toggle="tab" data-target="#photos-private-upload"><?php echo Lang::t('photo', 'Upload'); ?></a>
						<span class="line"></span>
					</li>
				</ul>
			</div>
			<!-- tabs -->
			<div class="tabs-content">
				<div id="photos-private-list" class="tabs-content-wrap photos-list <?php echo ($private_photos['data']) ? 'active' : '';?>">
					<!-- photos list -->
					<div class="photos-list" id="photo-list-private-active">
						<?php if(isset($private_photos['data'])) {?>
							<ul class="item-list" page="<?php echo $private_photos['next_page'];?>" type="<?php echo $private_photos['type'];?>">
								<?php foreach ($private_photos['data'] as $photo) {?>
								<li class="item" id="<?php echo $photo->id; ?>">
									<a class="ava" title="" href="javascript:void(0);" lis_me="true" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="viewPhotoDetail(this);">
										<?php echo $photo->getImageThumbnail();?>
										<span class="ava-bg"></span>
									</a>
									<div class="ulti ulti-done">
										<ul>
											<li><a class="btn-del" href="javascript:void(0);" onclick="javascript:Photo.delete_photo(this);"><i class="ismall ismall-x"></i></a></li>
										</ul>
									</div>
								</li>
								<?php } ?>
								 <?php if(sizeof($private_photos['data']) >= $private_photos['pages']->pageSize): ?>    
	                            <li class="item">
										<a class="btn-more-load" href="javascript:Photo.show_more('photo-list-private');">
											<label></label>
										</a>
								</li>  
								<?php endif; ?>
							</ul>
						<?php } ?>
					</div>
				</div>
				<!-- Public Photos List -->
				<div id="photos-private-upload" class="tabs-content-wrap photos-upload <?php echo (empty($private_photos['data'])) ? 'active' : '';?>">
				    <div class="photos-none" id="photos-none-photo-list-private">
						<p class="photos-none-des"><a onclick="javascript:Photo.upload_photo_open(this, 'private_upload_photo');"><label class="icon_add_photo"></label><?php echo Lang::t('photo', 'Click to add more photos'); ?></a></p>
					</div>
				    <?php
    				 	$this->widget('backend.extensions.EFineUploader.EFineUploader',
    						array(
    							'id'=>'private_upload_photo',
    							'config'=>array(
    								'multiple'=>true,
    								'autoUpload'=>true,
    				                'request'=>array(
    				                	'endpoint'=> $this->createUrl('photo/upload', array('type' => Photo::PRAVITE_PHOTO)),
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
    				<!-- if no photo display -->
					<div id="photo-list-private" class="photos-list">
						<ul class="item-list">
							<li class="item add-more" style="display: none;">
					   			<a class="btn-more" onclick="javascript:Photo.upload_photo_open(this, 'private_upload_photo');" href="javascript:void(0);"><span class="text">add more</span></a>
					   		</li>
						<ul>
					</div>
					<div class="right but_save_all" id="all-photo-list-private" style="display:none;">
                    	<div class="buttons">
                        	<button onclick="javascript:Photo.photoSaveAll(this, 'photo-list-private');" class="btn btn-violet"><?php echo Lang::t('photo', 'Save all');?></button>
                            <button onclick="javascript:Photo.photoDiscardAll(this, 'photo-list-private');" class="btn btn-white"><?php echo Lang::t('photo', 'Discard all');?></button>
						</div>
					</div>
				</div>
				<!-- Public Photos Upload -->
			</div>
			<!-- tabs content -->
		</div>
		<!-- Photos Upload Single Block: Private Photos Area -->