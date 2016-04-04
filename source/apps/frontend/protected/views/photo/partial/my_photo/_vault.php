		<!-- Photos Upload Single Block: Vault Photos Area -->
		<div class="photos-block photos-vault">
			<div class="tabs clearfix">
				<ul>
					<li rel="photo-list-vault" class="vault_photo active">
						<a href="javascript:void(0);"><?php echo Lang::t('photo', 'Vault Photos'); ?></a>
						<span class="line"></span>
					</li>
				</ul>
				<div class="num_photo">
					<?php 
						echo Lang::t('photo', 'You can upload {limit_photo} photos.', 
								array('{limit_photo}' => '<span class="limit-photo-count">'.Yii::app()->params['uploads']['photo']['limit_upload']['vault'].'</span>')
							);
					?>
				</div>
			</div>
			<!-- tabs -->
			<div class="tabs-content">
				<div id="photos-vault-list" class="tabs-content-wrap photos-list active">
					<!-- photos list -->
					<div class="photos-list" id="photo-list-vault-active">
						<ul class="item-list" id="photo-list-vault-view"page="<?php echo $vault_photos['next_page'];?>" type="<?php echo $vault_photos['type'];?>">
							<?php if(isset($vault_photos['data'])) {?>
								<?php foreach ($vault_photos['data'] as $photo) {?>
								<li class="item" id="<?php echo $photo->id; ?>">
									
									<a class="ava" title="" href="javascript:void(0);" lis_me="true" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);">
										<?php echo $photo->getImageThumbnail();?>
										<span class="ava-bg"></span>
									</a>
									<div class="ulti ulti-done">
										<ul>										    	
											<li><a class="btn-del" href="javascript:void(0);" onclick="javascript:Photo.delete_photo(this);"><i class="ismall ismall-x"></i></a></li>
										</ul>
									</div>
									<div class="chiase_hinhanh"><a href="javascript:void(o);" photoid="<?php echo $photo->id;?>" smallimg="<?php echo $photo->getImageThumbnail(true);?>" onclick="Photo.sharePhoto(this);"><?php echo Lang::t('photo', 'Share Photo To Everyone'); ?></a></div>
								</li>
								<?php } ?>
								<?php if($vault_photos['pages']->getItemCount() > $vault_photos['pages']->pageSize) {  ?>    
	                            <li class="item">
                            		<a class="btn-more-load" href="javascript:Photo.show_more('photo-list-vault');">
										<label></label>
									</a>
								</li>  
								<?php } ?>
							<?php } ?>
						</ul>
						<div class="photos-none" id="photos-none-photo-list-vault">
							<p class="photos-none-des"><a onclick="javascript:Photo.upload_photo_open(this, 'vault_upload_photo');"><label class="icon_add_photo"></label><?php echo Lang::t('photo', 'Click to add more photos'); ?></a></p>
						</div>
						
						<div id="popup-alert" class="photo-list-vault-dialog" style="display:none;">
							<?php
		    				 	$this->widget('backend.extensions.EFineUploader.EFineUploader',
		    						array(
		    							'id'=>'vault_upload_photo',
		    							'config'=>array(
		    								'multiple'=>true,
		    								'autoUpload'=>true,
		    				                'request'=>array(
		    				                	'endpoint'=> $this->createUrl('photo/upload', array('type' => Photo::VAULT_PHOTO)),
		    				                	'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
		    								),
		    								//'retry'=>array('enableAuto'=>true,'preventRetryResponseProperty'=>true),
		    				                'chunking'=>array('enable'=>true,'partSize'=>100),//bytes
		    				                'callbacks'=>array(
		    				                	'onProgress'=>"js:function(id, name, loaded, total){ Photo.photoBeforeUpload(); }",
		    				                	'onComplete'=>"js:function(id, name, response){ Photo.photoAfterUpload(response, 'photo-list-vault'); }",
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
							<div id="photo-list-vault-upload" class="photos-upload-list">
			                    <ul class="item-list item-list-1">
			                    </ul>
			                    <div class="add_save_all">
			                    	<div class="left"><a onclick="javascript:Photo.upload_photo_open(this, 'vault_upload_photo');" href="javascript:void(0);"><?php echo Lang::t('photo', 'Click to add more photos'); ?></a></div>
			                        <div class="right" id="all-photo-list-vault" style="display:none;">
			                        	<div class="buttons">
			                                <button onclick="javascript:Photo.photoSaveAll(this, 'photo-list-vault');" class="btn btn-violet"><?php echo Lang::t('photo', 'Save all');?></button>
			                                <button onclick="javascript:Photo.photoDiscardAll(this, 'photo-list-vault');" class="btn btn-white"><?php echo Lang::t('photo', 'Discard all');?></button>
			                            </div>
			                        </div>
			                    </div>
			                </div>
						</div>
					</div>
					<!-- photos list -->
				</div>
				<!-- Vault Photos List -->
			</div>
			<!-- tabs content -->
		</div>
		<!-- Photos Upload Single Block: Vault Photos Area -->
		        <div class="photo_share_one" style="display: none;">
                	<div class="left pics">
                    	<img src="#" align="right">
                    </div>
                    <div class="left txt">
                    	<p>
                    		<input name="txtusername_sharephoto" id="txtusername_sharephoto" type="text" value="" style="width: 236px;">
							<input type="hidden" name="sharephoto_photoid" id="sharephoto_photoid" value="0"/>
                    	</p>
                        <p><a class="but_share_photo" href="javascript:void(0);" onclick="Photo.sendSharePhoto();"><?php echo Lang::t('photo', 'Share Photo'); ?></a></p>
                    </div>
                </div>
                             <?php 
		                        $this->widget('backend.extensions.select2.ESelect2',array(
		                          'selector'=>"#txtusername_sharephoto",
		                          'options'=>array(
		                            'allowClear'=>true,
		                            'minimumInputLength' => 3,
									'maximumSelectionSize'	=>	1,
		                            'multiple'=>true,                            
		                            'ajax'=>array(
										'quietMillis'=> 800,
		                                'url'=>$this->user->createUrl('photo/SuggestUserSharePhoto'),
		                                'dataType'=>'json',
		                                'data'=>'js:function(term,page) { return {q: term, page_limit: 3, page: page}; }',
		                                'results'=>'js:function(data,page) {return {results: data}; }'
										
		                            ),
									'createSearchChoice'	=>	'js:function(term, data) { }'
		                          ),
								  
		                        ));
							?>