
<div class="main-wrap isu" style="width: 652px; margin:0 auto;">
	<!-- header but display at bottom -->
	<?php //$this->renderPartial('partial/filter');?>
	<!-- page filter -->
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'isu-form',
			'action' => ($model->isNewRecord) ? Yii::app()->createUrl('//isu/create') : Yii::app()->createUrl('//isu/edit', array('id' => $model->id)),	
			'enableAjaxValidation'=>false,
			'enableClientValidation'=>true,
	  		'clientOptions'=>array(
	  				'validateOnSubmit'=>true,
	  				'afterValidate'=> 'js:ISU.submit'
	  		),
		)); ?>
      <div class="form-contain form-isu">
        <div class="pop-title">
        	<h2><i class="i28 i28-isu"></i>
        		<span class="inline-text"><?php echo ($model->isNewRecord) ? Lang::t('isu', 'Create ISU') : Lang::t('isu', 'Edit');?></span>
        	</h2>
        </div>
        <div class="form-contain-wrap">
          <div class="isu-create hotbox-create">
            <div class="table-row">
              <label for=""><?php echo Lang::t('isu', 'Title');?></label>
              <div class="table-cell-right">
                <div class="input-wrap">
					<?php echo $form->textField($model,'title',array('placeholder'=>Lang::t('isu', 'Enter title...'))); ?>
					<?php echo $form->error($model,'title'); ?>
                </div>
              </div>
            </div>
            <!-- fake table row -->
            <!-- 
            <div class="table-row">
				<label for=""><?php echo Lang::t('isu', 'Location');?></label>
				<div class="table-cell-right">
					<div class="input-wrap">
						<?php echo $form->textField($model,'location',array('placeholder'=>Lang::t('isu', 'Enter location...'))); ?>
						<?php echo $form->error($model,'location'); ?>
					</div>
				</div>
			</div>
			 -->
            <div class="table-row">
				<label for=""><?php echo Lang::t('isu', 'Location');?></label>
				<div class="table-cell-right">
					<div class="input-wrap success">
						<?php 
						$country = new CountryonCache();
						$countrys = CHtml::listData($country->getListCountry(), 'id', 'name');
						$top_country = CHtml::listData(SysCountry::model()->getCountryTop(), 'id', 'name'); 
						$group_countrys =   array('----------- ' => $top_country, '-----------' => $countrys);
						
						$stage = new StateonCache();
						//$stages = $stage->getlistStateinCountry($model->country_id);
						$stages = $stage->getListState();
						
						$districts = array();
						
						/* $city = new CityonCache();
						$citys = $city->getlistCityinCountry($model->country_id); */
						?>
						<div class="select_style">
						<?php echo CHtml::activeDropDownList($model, 'country_id',$group_countrys, array('class' => 'virtual_form', 'text' =>  'select_country', 'empty' => Lang::t('isu', '-- Select Country --'))); ?>
						<span class="txt_select user_looking_textselect">
                        	<span class="select_country"> <?php echo Lang::t('isu', '-- Select Country --'); ?> </span>
                        </span> 
                        <span class="btn_combo_down"></span> 
						</div>
						<div class="select_style">
						<?php echo CHtml::activeDropDownList($model, 'city_id',CHtml::listData($stages, 'id', 'name'), array('class' => 'virtual_form', 'text' =>  'select_city', 'empty' => Lang::t('isu', '-- Select City --'))); ?>
						<span class="txt_select user_looking_textselect">
                        	<span class="select_city"> <?php echo Lang::t('isu', '-- Select City --'); ?> </span>
                        </span> 
                        <span class="btn_combo_down"></span>
						</div>
						<div class="select_style">
						<?php echo CHtml::activeDropDownList($model, 'district_id', array(), array('class' => 'virtual_form', 'text' =>  'select_district', 'empty' => Lang::t('isu', '-- Select District --'))); ?>
						<span class="txt_select user_looking_textselect">
                        	<span class="select_district"> <?php echo Lang::t('isu', '-- Select District --'); ?> </span>
                        </span> 
                        <span class="btn_combo_down"></span>
						</div>
						<?php echo $form->error($model,'country_id'); ?>
					</div>
				</div>
			</div>
			<!-- 
            <div class="table-row">
				<label for=""><?php //echo Lang::t('isu', 'City');?></label>
				<div class="table-cell-right">
					<div class="input-wrap">
						<?php echo CHtml::activeDropDownList($model, 'city_id',CHtml::listData($stages, 'id', 'name'), array('empty' => Lang::t('isu', '-- Select City --'))); ?>
						<?php echo $form->error($model,'city_id'); ?>
					</div>
				</div>
			</div>
			<div class="table-row">
				<label for=""><?php //echo Lang::t('isu', 'City');?></label>
				<div class="table-cell-right">
					<div class="input-wrap">
						<?php echo CHtml::activeDropDownList($model, 'district_id', array(), array('empty' => Lang::t('isu', '-- Select District --'))); ?>
						<?php echo $form->error($model,'district_id'); ?>
					</div>
				</div>
			</div>
			 -->
			
			<!-- fake table row -->
			<div class="table-row">
				<label for=""><?php echo Lang::t('isu', 'From');?></label>
				<div class="table-cell-right">
					<div class="input-wrap input-short" style="font-size: 12px;">
					<?php echo $form->textField($model,'date',array('id'=>'start_date', 'readonly' => 'readonly')); ?>
					<b><?php echo Lang::t('isu', 'To');?>&nbsp</b> 
					<?php echo $form->textField($model,'end_date',array('id'=>'end_date', 'readonly' => 'readonly', 'placeholder'=>Lang::t('isu', 'To'))); ?>
					
					<script type="text/javascript">
					$(function(){
						$('#start_date').appendDtpicker({
							"closeOnSelected": true,
							"locale": current_lang,
							
						});
						$('#end_date').appendDtpicker({
							"closeOnSelected": true,
							"locale": current_lang,
						});
					});
					</script>
					<?php
     					/* $this->widget ( 'backend.extensions.calendar.SCalendar', 
							array (
								'inputField' => 'isu_date_picker', 
								'button' => 'isu_date_picker', 
								'ifFormat' => '%Y-%m-%d',
							)

						); */
					?>
					<?php echo $form->error($model,'date'); ?>
					</div>
				</div>
			</div>
			<!-- fake table row -->
			<div class="table-row row-editor">
				<label for=""><?php echo Lang::t('isu', 'Content');?></label>
				<div class="table-cell-right">
					<div class="editor">
					    <?php 
                                    			$this->widget('backend.extensions.tinymce.TinyMce', array(
                                    			    'model' => $model,
                                    			    'attribute' => 'body',
                                    			    'htmlOptions' => array(
                                    			        'rows' => 15,
                                    			        'cols' => 10,
                                    			    ),
                                                    'settings' => array(
                                                       	'height' => '100px',
														'width' => '500px',
                                                        'theme_advanced_buttons1' => "bold,italic,underline,paste",
                                                        'theme_advanced_buttons2' => "",
                                                        'theme_advanced_buttons3' => "",
                                                        'theme_advanced_buttons4' => "",
                                                        'theme_advanced_resizing' => false,
														'theme_advanced_path' =>  false,
														'oninit'=> 'setPlainText',
                                                    ),
                                    			));
                                    		?>
						<?php echo $form->error($model,'body'); ?>
					</div>
				</div>
			</div>
			<!-- fake table row -->
			<div class="table-row row-photo">
				<label for=""><?php echo Lang::t('isu', 'Photo');?></label>
				<div class="table-cell-right">
					<button type="button" onclick="javascript:ISU.open_upload('upload-zone', <?php echo ($model->isNewRecord) ? 'true' : 'false';?>);" class="btn btn-white"><?php echo Lang::t('isu', 'Upload');?></button>
					<div class="input-wrap input-semi">
						<div class="upload-zone">
						<?php
						 	$this->widget('backend.extensions.EFineUploader.EFineUploader',
							array(
								'id'=>'FineUploader',
								'config'=>array(
									'multiple'=>false,
									'autoUpload'=>true,
					                'request'=>array(
					                	'endpoint'=> ($model->isNewRecord) ? Yii::app()->createUrl('//isu/upload') : Yii::app()->createUrl('//isu/upload', array('id' => $model->id)),
					                	'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
									),
									//'retry'=>array('enableAuto'=>true,'preventRetryResponseProperty'=>true),
					                'chunking'=>array('enable'=>true,'partSize'=>100),//bytes
					                'callbacks'=>array(
										'onProgress'=>"js:function(id, name, loaded, total){ ISU.process_upload(); }",
										'onComplete'=>"js:function(id, name, response){ ISU.complete_upload(response, 'hotbox_thumbnail'); }",
										'onError'=>"js:function(id, name, errorReason){ ISU.onError(errorReason); }",
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
						 <div class="hotbox_thumbnail">
		                	<ul>
		                		<?php if(isset($model->image)) {?>
			                    	<li id="isu-t">
				                    	<input type="hidden" name="Notes[image]" value="<?php echo $model->image;?>">
				                    	<input type="hidden" name="Notes[image_path]" value="<?php echo $model->image_path;?>">
				                    	<a href="javavascript:void(0);"><?php echo $model->getImageThumb();?></a>
				                    	<ol>
				                    		<li><a class="icon_del_thumb" onclick="ISU.delete_thumb('<?php echo $model->image;?>', '<?php echo $model->image_path;?>')"></a></li>
				                    	</ol>
			                    	</li>
		                    	<?php } ?>
		                    </ul> 
		                </div>
						
					</div>
					
				</div>
			</div>
			<!-- 
			<div class="table-row isu-option-thumbnail" style="<?php ($model->isNewRecord) ? 'display: none' : '';?>">
				<label for=""><?php echo Lang::t('isu', 'Option');?></label>
				<div class="table-cell-right">
					<div class="input-wrap input-short">
					<?php echo $form->checkBox($model,'image_in_body'); ?>
					<?php echo $form->error($model,'image_in_body'); ?>
					</div>
					<?php echo Lang::t('isu', 'Use this image for your ISU');?>
				</div>
			</div>
			 -->
			<!-- fake table row -->
			<div class="table-row">
				<label for=""></label>
				<div class="table-cell-right">
					<div class="buttons">
						<button id="isu-submit" class="btn btn-violet"><?php echo Lang::t('isu', 'Submit');?></button>
						<button id="isu-cancel" type="button" onclick="ISU.form_back();" class="btn btn-white"><?php echo Lang::t('isu', 'Back');?></button>
					</div>
				</div>
			</div>
			<!-- fake table row -->
		</div>
		<!-- create ISU form -->
		</div>
		</div>
		<div class="position"></div>
		<?php $this->endWidget(); ?>
		<!-- form container -->
	</div>
	<!-- wrap -->
</div>	
			
<script>
	sprScroll('.main .main-wrap');
	$("#Notes_country_id").change(function(){
		if($(this).val() != '') {
			//$("#Notes_city_id").hide();
			$(this).loading();
			var url = "<?php echo str_replace("/", "\/", Yii::app()->createUrl('//isu/GetCityList')); ?>";
			$.ajax({
				type: "GET",
				url: url,
				data: { country_id: $(this).val()}
			}).done(function( response ) {
				if(response.length > 0){
					$("#Notes_city_id").show();
					$("#Notes_district_id").show();
					$("#Notes_city_id").html(response);
				} else {
					$("#Notes_city_id").hide();
					$("#Notes_district_id").hide();
				}
				$(this).unloading();
			});	
		}
		
	});
	$("#Notes_city_id").change(function(){
		if($(this).val() != '') {
			//$("#Notes_city_id").hide();
			var url = "<?php echo str_replace("/", "\/", Yii::app()->createUrl('//isu/GetDistrictList')); ?>";
			$.ajax({
				type: "GET",
				url: url,
				data: { city_id: $(this).val()}
			}).done(function( response ) {
				$("#Notes_district_id").html(response);
			});	
		}
		
	});
</script>
<script type="text/javascript" charset="utf-8">
    /*tinymce.init({
      oninit : "setPlainText",
      plugins : "paste"
    });*/
    function setPlainText() {
      var ed = tinyMCE.get('Notes_body');

      ed.pasteAsPlainText = true;  
      if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
          ed.onKeyDown.add(function (ed, e) {
              if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
                  ed.pasteAsPlainText = true;
          });
      } else {            
          ed.onPaste.addToTop(function (ed, e) {
              ed.pasteAsPlainText = true;
          });
      }
  }
</script>