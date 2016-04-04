	<div id="" class="popup-common popup_share_photo">
		<div class="wrap-popup">
        	<a href="<?php echo $user->createUrl('photo/index'); ?>" class="icon-common icon-back">&nbsp;</a>            
        	<div class="form_share_photo">
            	<h3><?php echo Lang::t('photo', 'Share Photo'); ?></h3>
            	<div class="photo_share">
                	<p><?php echo $photo_detail->getImageThumbnail(false, array('align' => 'absmiddle', 'width' => '120px', 'height' => '120px'));?></p>
                    <p>
                            <input name="txtusername_sharephoto" id="txtusername_sharephoto" type="text" value="" style="width: 279px;">
							<input type="hidden" name="sharephoto_photoid" id="sharephoto_photoid" value="<?php echo $photoid; ?>"/>
                    </p>
                    <div class="share_cancel">
                    	<a href="javascript:void(0);" class="share" onclick="Photo.sendSharePhoto('<?php echo $user->alias_name; ?>');"><?php echo Lang::t('photo', 'Share'); ?></a>
                        <a href="<?php echo $user->createUrl('photo/index'); ?>" class=""><?php echo Lang::t('settings', 'Back'); ?></a>
                    </div>
                </div>
			</div>
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
										'quietMillis'=> 700,
		                                'url'=> $user->createUrl('photo/SuggestUserSharePhoto'),
		                                'dataType'=>'json',
		                                'data'=>'js:function(term,page) { return {q: term, page_limit: 3, page: page}; }',
		                                'results'=>'js:function(data,page) {return {results: data}; }'
										
		                            ),
									'createSearchChoice'	=>	'js:function(term, data) { }'
		                          ),
								  
		                        ));
							?>