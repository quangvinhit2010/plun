<?php 
	$cs = Yii::app()->clientScript;
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/photo.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/avatar.js?t=' .time(), CClientScript::POS_END);
	Yii::app()->clientScript->registerCss('photos_css', '.setAvatarWrap {
		left: 50% !important;
		margin-left: -48PX;
		-webkit-transform: translateX(-25%);
		transform: translateX(-25%);
		-o-transform: translateX(-25%);
		-ms-transform: translateX(-25%);
		-moz--transform: translateX(-25%);
	}');
	$type = Yii::app()->request->getParam('type', Photo::PUBLIC_PHOTO);
?>
<div class="list_explore photo_video">
	<?php if($isMe): ?>
	<div style="opacity: 0; visibility: hidden; position: absolute; left: -10000px">
		<div class="popup_genneral popup_share_photo">
	        <div class="title"><?php echo Lang::t('photo', 'Share Photo'); ?></div>
	        <div class="content">
	            <div class="left"><img class="share-thumb" src="images/avatar_100x100.jpg" width="65" height="65" /></div>
	            <div class="left txt_share">
	                <p><input name="txtusername_sharephoto" id="txtusername_sharephoto" type="text" value="" style="width: 210px;" /></p>
	                <input type="hidden" name="sharephoto_photoid" id="sharephoto_photoid" value="0"/>
					<p><input class="but" name="" type="button" value="<?php echo Lang::t('photo', 'Share'); ?>" onclick="Photo.sendSharePhoto();" /></p>
				</div>
			</div>
		</div>
                    <?php
                        $this->widget('backend.extensions.select2.ESelect2',array(
                            'selector' => "#txtusername_sharephoto",
                            'options' => array(
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'maximumSelectionSize'	=>	1,
                                'multiple' => true,
                                'ajax' => array(
                                    'quietMillis' => 800,
                                    'url' => $this->user->createUrl('photo/SuggestUserSharePhoto'),
                                    'dataType' => 'json',
                                    'data' => 'js:function(term,page) { return {q: term, page_limit: 3, page: page}; }',
                                    'results' => 'js:function(data,page) {return {results: data}; }'
                                ),
                                'createSearchChoice'	=>	'js:function(term, data) { }'
                            ),
                        ));
                    ?>
		<?php
			$this->widget('backend.extensions.EFineUploader.EFineUploader',
				array(
					'id' => 'upload_photo',
					'config' => array(
					'multiple' => true,
					'maxConnections' => true,
					'autoUpload' => true,
					'request' => array(
						'endpoint' => $this->createUrl('photo/upload'),
						'params' => array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
					),
					'chunking' => array('enable'=>true,'partSize'=>100),
                    'callbacks' => array(
                    	'onSubmit' => "js:function(id, name){ Photo.onUpload(id, name); }",
                    	'onProgress' => "js:function(id, name, loaded, total){ Photo.photoBeforeUpload(id, name, loaded, total); }",
                    	'onComplete' => "js:function(id, name, response){ Photo.photoAfterUpload(id, name, response); }",
                    	'onError' => "js:function(id, name, errorReason){ Photo.photoOnError(id, name, errorReason); }",
                    ),
					'validation' => array(
						'allowedExtensions'=>array('jpg','jpeg', 'png'),
						'sizeLimit'=>20 * 1024 * 1024,
					),
				)
			));
		?>
	</div>
	<?php endif; ?>
	<?php
		$partialFolder = $isMe ? 'my' : 'your';
		foreach ($mappings as $partial => $photos) {
			$partialFile = "partial/$partialFolder/$partial";
			$this->renderPartial($partialFile, array('photos'=>$photos));
		}
	?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.photo_video .photo-group .content').each(function(){
            $(this).find('> ul > li').boxResizeImg({
                parentWrap: '.photo-group .content',
                wRealBox: 160,
                hRealBox: 160
            });
        });
        objCommon.tooltipPlun({
            el: '.photo_public .titlePhoto h4 span.icon_common',
            posiRight: true,
            titleTip: '<?php echo Lang::t('photo', 'Public Photos are the photos which are accessible to other users, the maximum number for your Public Photos is {limit}.', array('{limit}'=>Yii::app()->params['uploads']['photo']['limit_upload']['private']))?>'
        });
        objCommon.tooltipPlun({
            el: '.photo_vault .titlePhoto h4 span.icon_common',
            posiRight: true,
            titleTip: '<?php echo Lang::t('photo', 'Vault Photos are the photos which are only accessible to the users sending request to you. The maximum number for VP is {limit}.', array('{limit}'=>Yii::app()->params['uploads']['photo']['limit_upload']['vault']))?>'
        });
        objCommon.tooltipPlun({
            el: '.photo_private .titlePhoto h4 span.icon_common',
            posiRight: true,
            titleTip: '<?php echo Lang::t('photo', 'Viewers will pay you Candies for each photo they view. The maximum number for Private Photo is {limit}.', array('{limit}'=>Yii::app()->params['uploads']['photo']['limit_upload']['private']))?>'
        });

        $('.sticky_column').fixed_col_scroll();
    });
</script>