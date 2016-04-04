		<!-- Photos Upload Single Block: Public Photos Area -->
		<div class="photos-block photos-public">
			<div class="tabs clearfix">
				<ul>
					<li rel="photo-list-public" class="public_photo active">
						<a href="javascript:void(0);"><?php echo Lang::t('photo', 'Public Photos'); ?></a>
						<span class="line"></span>
					</li>
				</ul>
				<div class="num_photo">
					<?php 
						echo Lang::t('photo', 'You can upload {limit_photo} photos.', 
								array('{limit_photo}' => '<span class="limit-photo-count">'.Yii::app()->params['uploads']['photo']['limit_upload']['public'].'</span>')
							);
					?>
				</div>
			</div>
			<!-- tabs -->
			<div class="tabs-content">
				<div id="photos-public-list"  class="tabs-content-wrap photos-list active">
					<div id="photo-list-public-active" class="photos-list">
						<ul class="item-list" id="photo-list-public-view" page="<?php echo $public_photos['next_page'];?>" type="<?php echo $public_photos['type'];?>">
							<?php if($public_photos['pages']->getItemCount() > 0) {?>
								<?php foreach ($public_photos['data'] as $photo) {?>
								<li class="item" id="<?php echo $photo->id; ?>">
									<a class="ava" title="" href="javascript:void(0);" lis_me="true" data-photo-id="<?php echo $photo->id;?>" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);">
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
								<?php if($public_photos['pages']->getItemCount() > $public_photos['pages']->pageSize ){ ?>    
	                            <li class="item">
										<a class="btn-more-load" href="javascript:Photo.show_more('photo-list-public');">
											<label></label>
										</a>
								</li>  
								<?php } ?>
							<?php } ?>
						</ul>
						
						<div class="photos-none" id="photos-none-photo-list-public">
							<p class="photos-none-des"><a onclick="javascript:Photo.upload_photo_open(this, 'public_upload_photo');"><label class="icon_add_photo"></label><?php echo Lang::t('photo', 'Click to add more photos'); ?></a></p>
						</div>
						<!-- if no photo display -->
						<div id="popup-alert" class="photo-list-public-dialog"  style="display:none;">
							<?php
		    				 	$this->widget('backend.extensions.EFineUploader.EFineUploader',
		    						array(
		    							'id'=>'public_upload_photo',
		    							'config'=>array(
		    								'multiple'=>true,
		    								'autoUpload'=>true,
		    				                'request'=>array(
		    				                	'endpoint'=> $this->createUrl('photo/upload', array('type' => Photo::PUBLIC_PHOTO)),
		    				                	'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
		    								),
		    								//'retry'=>array('enableAuto'=>true,'preventRetryResponseProperty'=>true),
		    				                'chunking'=>array('enable'=>true,'partSize'=>100),//bytes
		    				                'callbacks'=>array(
												'onProgress'=>"js:function(id, name, loaded, total){ Photo.photoBeforeUpload(); }",
		    				                	'onComplete'=>"js:function(id, name, response){ Photo.photoAfterUpload(response, 'photo-list-public'); }",
		    				                    'onError'=>"js:function(id, name, errorReason){ Photo.photoOnError(errorReason);}",
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
							<div id="photo-list-public-upload" class="photos-upload-list">
			                    <ul class="item-list item-list-1">
			                    </ul>
			                    <div class="add_save_all">
			                    	<div class="left"><a onclick="javascript:Photo.upload_photo_open(this, 'public_upload_photo');" href="javascript:void(0);"><?php echo Lang::t('photo', 'Click to add more photos'); ?></a></div>
			                        <div class="right" id="all-photo-list-public" style="display:none;">
			                        	<div class="buttons">
			                                <button onclick="javascript:Photo.photoSaveAll(this, 'photo-list-public');" class="btn btn-violet"><?php echo Lang::t('photo', 'Save all');?></button>
			                                <button onclick="javascript:Photo.photoDiscardAll(this, 'photo-list-public');" class="btn btn-white"><?php echo Lang::t('photo', 'Discard all');?></button>
			                            </div>
			                        </div>
			                    </div>
			                </div>
						</div>
					</div>
					<!-- photos list -->
				</div>
				<!-- Public Photos List -->
			</div>
			<!-- tabs content -->
		</div>
		<!-- Photos Upload Single Block: Public Photos Area -->