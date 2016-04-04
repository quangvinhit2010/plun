<div class="main-wrap isu" style="width: 788px; margin:0 auto;">
	<!-- header but display at bottom -->
	<?php //$this->renderPartial('partial/filter');?>
	<!-- page filter -->
	<div class="col-full">
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
      <div class="form-contain form-isu">
        <div class="pop-title">
        	<h2><i class="i28 i28-isu"></i>
        		<span class="inline-text"><?php echo ($model->isNewRecord) ? Lang::t('hotbox', 'Create HOTBOX') : Lang::t('hotbox', 'Edit HOTBOX');?></span>
        	</h2>
        </div>
        <div class="form-contain-wrap">
          <div class="isu-create hotbox-create">
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
                            	'plugins' => '',                                                        
                            	'height' => '100px',                                                        
                                'width' => '500px',
                                'theme_advanced_buttons1' => "bold,italic,underline",
                                'theme_advanced_buttons2' => "",
                                'theme_advanced_buttons3' => "",
                                'theme_advanced_buttons4' => "",
                                'theme_advanced_resizing' => false,
								'theme_advanced_path' =>  false,
				
							),
                    	));
					?>
					<?php echo $form->error($model,'body'); ?>
                	
                </div>
              </div>
            </div>
            <?php //$section = ($model->isNewRecord) ? array('disabled' => 'disabled') : null; ?>
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
									'itemLimit' => 1
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
