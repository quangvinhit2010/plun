<?php
$cs = Yii::app()->getClientScript();

$css = ".ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
		.ui-timepicker-div dl { text-align: left; }
		.ui-timepicker-div dl dt { float: left; clear:left; padding: 0 0 0 5px; }
		.ui-timepicker-div dl dd { margin: 0 10px 10px 45%; }
		.ui-timepicker-div td { font-size: 90%; }
		.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
		
		.ui-timepicker-rtl{ direction: rtl; }
		.ui-timepicker-rtl dl { text-align: right; padding: 0 5px 0 0; }
		.ui-timepicker-rtl dl dt{ float: right; clear: right; }
		.ui-timepicker-rtl dl dd { margin: 0 45% 10px 10px; }
		#ui-datepicker-div { left: -1000px;}
		.ui-timepicker-div, .ui-datepicker .ui-datepicker-buttonpane { display: block !important; color: #222222;}
		.ui-slider-horizontal {background: #ECE5E5 !important;}
		.ui-slider-handle {margin-top: 2px;}
		.ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus {color: #222222 !important}
		.ui-datepicker-header .ui-icon {top: 13px !important; left: 14px !important;}
		.ui-state-active, .ui-widget-content .ui-state-active {color: white !important; background: #662d91 !important}";
$cs->registerCss('show-header', $css);
?>
<div class="main-wrap isu" style="width: 665px; margin:0 auto;">
	<!-- header but display at bottom -->
	<?php //$this->renderPartial('partial/filter');?>
	<!-- page filter -->
	<div class="col-full" style="overflow: visible;">
		<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'hotbox-form',
		'action' => ($model->isNewRecord) ? Yii::app()->createUrl('//hotbox/create') : Yii::app()->createUrl('//hotbox/edit', array('id' => $model->id)),	
  		'enableAjaxValidation'=>false,
		'enableClientValidation'=>true,
  		'clientOptions'=>array(
  				'validateOnSubmit'=>true,
  				'afterValidate'=> 'js:Hotbox.submit'
  		),
  		
	)); ?>
		<?php echo CHtml::activeHiddenField($model, 'type'); ?>
      <div class="form-contain form-isu">
        <div class="pop-title">
        	<h2><i class="i28 i28-isu"></i>
        		<span class="inline-text"><?php echo ($model->isNewRecord) ? Lang::t('hotbox', 'Create New') : Lang::t('hotbox', 'Edit HOTBOX');?></span>
        	</h2>
        </div>
        <div class="form-contain-wrap" style="margin-bottom: 60px;">
          <?php if($model->isNewRecord): ?>
          <div id="link-wrap">
          	<div id="photo-link"><a data-type="<?php echo Hotbox::PHOTO ?>" href="#"><?php echo Lang::t('hotbox', 'Photo') ?></a></div>
          	<div id="event-link"><a data-type="<?php echo Hotbox::EVENT ?>" href="#"><?php echo Lang::t('hotbox', 'Event') ?></a></div>
          	<div class="btn-wrap">
          		<button type="button" onclick="Hotbox.button_cancel();" class="btn btn-white"><?php echo Lang::t('hotbox', 'Back');?></button>
          	</div>
          </div>
          <?php endif; ?>
          <div class="isu-create hotbox-create"<?php echo ($model->isNewRecord) ? ' style="display: none;"': '' ?>>
            <div class="table-row">
              <label for=""><?php echo Lang::t('hotbox', 'Title');?></label>
              <div class="table-cell-right">
                <div class="input-wrap">
                	<?php echo $form->textField($model,'title',array('placeholder'=>Lang::t('hotbox', 'Enter title...'))); ?>
					<?php echo $form->error($model,'title'); ?>
                </div>
              </div>
            </div>
            <!-- fake table row -->
            <!-- 
            <div class="table-row">
              <label for=""><?php echo Lang::t('hotbox', 'Description');?></label>
              <div class="table-cell-right">
                <div class="input-wrap">
                	<?php echo $form->textArea($model,'description',array('placeholder'=>Lang::t('hotbox', 'Enter description...'))); ?>
					<?php echo $form->error($model,'description'); ?>
                </div>
              </div>
            </div>
             -->
            <!-- fake table row -->
            <div class="table-row row-editor">
              <label for=""><?php echo Lang::t('hotbox', 'Content');?></label>
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
                                'theme_advanced_buttons1' => "bold,italic,underline,removeformat,paste",
                                'theme_advanced_buttons2' => "",
                                'theme_advanced_buttons3' => "",
                                'theme_advanced_buttons4' => "",
                                'theme_advanced_resizing' => false,
                								'theme_advanced_path' =>  false,
                                'oninit'=> 'setPlainText',
                            ),
                    	));
					?>
                    <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/util/plugin-tiny/my.tinymce.plugin.js"></script>
                    <script type="text/javascript" charset="utf-8">
                        /*tinymce.init({
                          oninit : "setPlainText",
                          plugins : "paste"
                        });*/
                        function setPlainText() {
                          var ed = tinyMCE.get('HotboxForm_body');

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

					<?php echo $form->error($model,'body'); ?>
                	
                </div>
              </div>
            </div>
            <?php //$section = ($model->isNewRecord) ? array('disabled' => 'disabled') : null; ?>
            
            <!-- 
            <div class="table-row">
              <label for=""><?php echo Lang::t('hotbox', 'Sections');?></label>
              <div class="table-cell-right">
                <div class="input-wrap">
					<?php if($model->isNewRecord){ ?>
						<?php echo CHtml::activeDropDownList(
                			$model, 'type',array(Hotbox::PHOTO => 'Photo', Hotbox::EVENT => 'Event'),
                			array('onchange' => 'Hotbox.change_type(this)')); 
						?>
					<?php  } else { ?>				
						<?php echo CHtml::activeDropDownList(
								$model, 'type',array(Hotbox::PHOTO => 'Photo', Hotbox::EVENT => 'Event'),
								array('onchange' => 'Hotbox.change_type(this)', 'disabled' => 'disabled')); 
						?>
					<?php } ?>
					<?php echo $form->error($model,'type'); ?>
                </div>
              </div>
            </div>
            
             -->
			<div class="table-row row-where"<?php if($model->type=='2') echo ' style="display:none;"' ?>>
              <label for=""><?php echo Lang::t('hotbox', 'Where');?></label>
              <div class="table-cell-right">
                <div class="input-wrap">
					<div class="select_style w160">
						<?php
							$list_country = CHtml::listData($list_country, 'id', 'name');
							echo CHtml::dropDownList('HotboxForm[country_id]', $model->country_id, $list_country, array('onchange' => 'getStateRegister();', 'name' => 'txt-country','id' => 'select-country','class' => 're-country virtual_form', 'text' => 'country_register_text'));
						?>
						<span class="txt_select"><span class="country_register_text"><?php echo $list_country[$model->country_id]; ?></span></span> <span class="btn_combo_down"></span>
					</div>
					
					<?php $display = $list_state ? '' : 'display: none' ?>
					<div class="select_style w160" style="<?php echo $display ?>">
						<?php
							$list_state = CHtml::listData($list_state, 'id', 'name');
							echo CHtml::dropDownList('HotboxForm[state_id]',$model->state_id, $list_state, array('name' => 'txt-state', 'id' => 'select-state', 'onchange' => "getCityRegister();",'class' => 're-state virtual_form', 'text' => 'state_register_text','empty' => Lang::t('search', '--Any--'))); 
						?>
						<span class="txt_select"><span class="state_register_text"><?php if($model->state_id) echo $list_state[$model->state_id]; ?></span></span> <span class="btn_combo_down"></span>
					</div>
					
					<?php $display = sizeof($list_city) ? '' : 'display: none' ?>
					<div class="select_style w160" style="<?php echo $display ?>">
						<?php
							$list_city = CHtml::listData($list_city, 'id', 'name');
							$city_select	=	isset($list_city[$model->city_id])	?	$list_city[$model->city_id]	:	Lang::t('search', '--Any--');
							echo CHtml::dropDownList('HotboxForm[city_id]',$model->city_id, $list_city, array('name' => 'txt-city', 'id' => 'select-city', 'onchange' => "getDistrictRegister();",'class' => 're-city virtual_form', 'text' => 'city_register_text', 'empty' => Lang::t('search', '--Any--'))); 
						?>
						<span class="txt_select"><span class="city_register_text"><?php echo $city_select; ?></span></span> <span class="btn_combo_down"></span>
					</div>
					
					<?php $display = sizeof($list_district) ? '' : 'display: none' ?>
					<div class="select_style w160" style="margin-top: 10px;<?php echo $display ?>">
						<?php
							$list_district = CHtml::listData($list_district, 'id', 'name');
							$district_select	=	isset($list_district[$model->district_id])	?	$list_district[$model->district_id]	:	Lang::t('search', '--Any--');
							echo CHtml::dropDownList('HotboxForm[district_id]',$model->district_id, $list_district, array('name' => 'txt-district', 'id' => 'select-district', 'class' => 're-district virtual_form', 'text' => 'district_register_text', 'empty' => Lang::t('search', '--Any--'))); 
						?>
						<span class="txt_select"><span class="district_register_text"><?php echo $district_select; ?></span></span> <span class="btn_combo_down"></span>
					</div>
                </div>
              </div>
            </div>
            <div class="table-row row-when"<?php if($model->type=='2') echo ' style="display:none;"' ?>>
              <label for="when_from"><?php echo Lang::t('hotbox', 'When');?></label>
              <div class="table-cell-right">
                <div class="input-wrap">
                	<?php if($model->isNewRecord): ?>
					<?php echo $form->textField($model,'start',array('id'=>'when-from', 'value'=>date('d-m-Y h:i'))); ?>
					<label class="to_label" for="when_to"><?php echo Lang::t('hotbox', 'To');?></label>
					<?php echo $form->textField($model,'end',array('id'=>'when-to', 'value'=>date('d-m-Y h:i'))); ?>
					<?php else: ?>
					<?php echo $form->textField($model,'start',array('id'=>'when-from', 'value'=>$model->start)); ?>
					<label class="to_label" for="when_to"><?php echo Lang::t('hotbox', 'To');?></label>
					<?php echo $form->textField($model,'end',array('id'=>'when-to', 'value'=>$model->end)); ?>
					<?php endif; ?>
					<?php echo $form->error($model,'end'); ?>
                </div>
              </div>
            </div>
            
            <!-- 
            <div class="table-row row-event" <?php echo (!$model->events) ? 'style="display: none;"' : null;?>>
              <label for=""><?php echo Lang::t('hotbox', 'Events');?></label>
              <div class="table-cell-right">
                <div class="input-wrap">
                	<?php echo $form->textArea($model,'tmp_event_title',array('placeholder'=>Lang::t('hotbox', 'Enter event detail...'))); ?>
                	<?php echo $form->error($model,'tmp_event_title'); ?>
                	<?php echo $form->textField($model,'tmp_event_start_date',array('id'=>'start_date', 'placeholder'=>Lang::t('hotbox', 'Enter event start date...'))); ?>
    				<?php
     					$this->widget ( 'backend.extensions.calendar.SCalendar', 
							array (
								'inputField' => 'start_date', 
								'button' => 'start_date', 
								'ifFormat' => '%Y-%m-%d' ) 
						);
					?>
					<?php echo $form->error($model,'tmp_event_start_date'); ?>
                	<?php echo $form->textField($model,'tmp_event_end_date',array('id'=>'end_date', 'placeholder'=>Lang::t('hotbox', 'Enter event end date...'))); ?>
    				<?php
     					$this->widget ( 'backend.extensions.calendar.SCalendar', 
							array (
								'inputField' => 'end_date', 
								'button' => 'end_date', 
								'ifFormat' => '%Y-%m-%d' ) 
						);
					?>
					<?php echo $form->error($model,'tmp_event_end_date'); ?>
                </div>
              </div>
            </div>
             -->
            <!-- fake table row -->
            <div class="table-row row-photo">
              <label for=""><?php echo Lang::t('hotbox', 'Upload');?></label>
              <div class="table-cell-right">
              	<button type="button" onclick="javascript:Hotbox.open_upload('upload-zone');" class="btn btn-white"><?php echo Lang::t('hotbox', 'Upload');?></button>
                <div class="input-wrap input-semi">
                	<div class="upload-zone">
                	<?php
                		// change 1 -> n for limit upload on creating event hotbox 
                		$limit = ($model->type == Hotbox::EVENT) ? 1 : 1;
                		$upload = ($model->isNewRecord) ? Yii::app()->createUrl('//hotbox/upload') : Yii::app()->createUrl('//hotbox/upload', array('id' => $model->id));
					 	$this->widget('backend.extensions.EFineUploader.EFineUploader',
						array(
							'id'=>'FineUploader',
							'config'=>array(
								'multiple'=>true,
								'autoUpload'=>true,
				                'request'=>array(
				                	'endpoint'=> $upload,
				                	'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
								),
								//'retry'=>array('enableAuto'=>true,'preventRetryResponseProperty'=>true),
				                'chunking'=>array('enable'=>true,'partSize'=>100),//bytes
				                'callbacks'=>array(
									'onProgress'=>"js:function(id, name, loaded, total){ Hotbox.process_upload(); }",
				                	'onComplete'=>"js:function(id, name, response){ Hotbox.complete_upload(response, 'hotbox_thumbnail'); }",
				                    'onError'=>"js:function(id, name, errorReason){ Hotbox.onError(errorReason); }",
								),
				                'validation'=>array(
									'allowedExtensions'=>array('jpg','jpeg', 'png'),
									'sizeLimit'=>20 * 1024 * 1024,//maximum file size in bytes
									//'minSizeLimit'=>2*1024*1024,// minimum file size in bytes
									'itemLimit' => $limit
								),
							)
						));
					?>
					</div>
                </div>
                <div class="hotbox_thumbnail">
                	<ul>
                		<?php if(isset($model->images)) {?>
	                		<?php foreach ($model->images as $image) {?>
	                    	<li id="hb_t_<?php echo $image->id;?>">
		                    	<input type="hidden" name="HotboxForm[tmp_images][]" value="<?php echo $image->id;?>">
		                    	<a href="javavascript:void(0);"><?php echo $image->getImageThumb();?></a>
		                    	<ol>
		                    		<li><a class="icon_del_thumb" onclick="Hotbox.delete_thumb(<?php echo $image->id;?>)"></a></li>
		                    		<li class="hb_default_t"><a class="<?php echo ($image->id == $model->thumbnail_id) ? 'icon_check_thumb_active' : 'icon_check_thumb' ;?>" onclick="Hotbox.set_thumb(<?php echo $image->id;?>)"></a></li>
		                    	</ol>
	                    	</li>
	                    	<?php } ?>
                    	<?php } ?>
                    </ul> 
                </div>
                <div id="thumbnail_id"></div>
                <div class="notify_create_hb" <?php echo (empty($model->images)) ? 'style="display:none;"' : '' ;?>>
                	<p><label class="check_thumb"></label> <label><?php echo Lang::t('hotbox', 'Set thumbnail');?></label> <label class="del_thumb"></label>  <label><?php echo Lang::t('hotbox', 'Delete image');?></label></p>
                </div>
              </div>
            </div>
            
            
            <!-- fake table row -->
            <div class="table-row">
              <label for=""></label>
              <div class="table-cell-right">
                <div class="buttons">
                  <button type="submit" class="btn btn-violet" id="hotbox-submit"><?php echo Lang::t('hotbox', 'Sumbit');?></button>
                  <button type="button" onclick="Hotbox.button_cancel();" class="btn btn-white"><?php echo Lang::t('hotbox', 'Back');?></button>
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
	</div>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/jquery-ui-timepicker-addon.js"></script>
