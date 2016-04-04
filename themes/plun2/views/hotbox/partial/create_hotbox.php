<div class="popup_hotbox_event">
	<div class="title"><?php echo ($model->isNewRecord) ? Lang::t('hotbox', 'Create New') : Lang::t('hotbox', 'Edit HOTBOX') ?></div>
	<div class="content">
		<div class="hotbox-create isu-create">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'action' => ($model->isNewRecord) ? Yii::app()->createUrl('//hotbox/create') : Yii::app()->createUrl('//hotbox/edit', array('id' => $model->id)),	
		  		'enableAjaxValidation'=>false,
		  		'clientOptions'=>array(
		  				'validateOnSubmit'=>true,
		  				'afterValidate'=> 'js:hotbox.submit'
		  		),
				'htmlOptions'=>array(
					'class'=>'hotbox-form',
				),
			)); ?>
			<?php echo CHtml::activeHiddenField($model, 'type'); ?>
			<div class="table-row">
				<label for=""><?php echo Lang::t('hotbox', 'Title') ?></label>
				<div class="table-cell-right">
					<div class="input-wrap">
						<?php echo $form->textField($model,'title',array('placeholder'=>Lang::t('hotbox', 'Enter title...'))); ?>
						<div style="display: none;" id="HotboxForm_title_em_" class="errorMessage"></div>
					</div>
				</div>
			</div>
			<div class="table-row where">
				<label for=""><?php echo Lang::t('hotbox', 'Where') ?></label>
				<div class="table-cell-right">
					<div class="input-wrap">
						<?php
							$list_country = CHtml::listData($list_country, 'id', 'name');
							$top_country = CHtml::listData(SysCountry::model()->getCountryTop(), 'id', 'name');
							$group_countrys =   array('----------- ' => $top_country, '-----------' => $list_country);
							echo CHtml::dropDownList('HotboxForm[country_id]', $model->country_id, $group_countrys, array('onchange' => 'getStateRegister();', 'name' => 'txt-country','id' => 'select-country','class' => 're-country virtual_form', 'text' => 'country_register_text'));
						?>
						<?php
							$display = $list_state ? '' : 'display: none';
							$list_state = CHtml::listData($list_state, 'id', 'name');
							echo CHtml::dropDownList('HotboxForm[state_id]',$model->state_id, $list_state, array('style'=>$display, 'name' => 'txt-state', 'id' => 'select-state', 'onchange' => "getCityRegister();",'class' => 're-state virtual_form', 'text' => 'state_register_text','empty' => Lang::t('search', '--Any--'))); 
						?>
						<?php
							$display = sizeof($list_city) ? '' : 'display: none';
							$list_city = CHtml::listData($list_city, 'id', 'name');
							$city_select	=	isset($list_city[$model->city_id])	?	$list_city[$model->city_id]	:	Lang::t('search', '--Any--');
							echo CHtml::dropDownList('HotboxForm[city_id]',$model->city_id, $list_city, array('style'=>$display, 'name' => 'txt-city', 'id' => 'select-city', 'onchange' => "getDistrictRegister();",'class' => 're-city virtual_form', 'text' => 'city_register_text', 'empty' => Lang::t('search', '--Any--'))); 
						?>
						<?php
							$display = sizeof($list_district) ? '' : 'display: none';
							$list_district = CHtml::listData($list_district, 'id', 'name');
							$district_select	=	isset($list_district[$model->district_id])	?	$list_district[$model->district_id]	:	Lang::t('search', '--Any--');
							echo CHtml::dropDownList('HotboxForm[district_id]',$model->district_id, $list_district, array('style'=>$display, 'name' => 'txt-district', 'id' => 'select-district', 'class' => 're-district virtual_form', 'text' => 'district_register_text', 'empty' => Lang::t('search', '--Any--'))); 
						?>
					</div>
				</div>
			</div>
			<div class="table-row when">
				<label for=""><?php echo Lang::t('hotbox', 'When') ?></label>
				<div class="table-cell-right">
					<div class="input-wrap">
						<?php if($model->isNewRecord): ?>
						<?php echo $form->textField($model,'start',array('class'=>'from_time', 'id'=>'when-from', 'value'=>date('d-m-Y h:i'))); ?>
						<label class="goto" for="when_to"><?php echo Lang::t('hotbox', 'To');?></label>
						<?php echo $form->textField($model,'end',array('class'=>'from_time', 'id'=>'when-to', 'value'=>date('d-m-Y h:i'))); ?>
						<?php else: ?>
						<?php echo $form->textField($model,'start',array('class'=>'from_time', 'id'=>'when-from', 'value'=>$model->start)); ?>
						<label class="goto" for="when_to"><?php echo Lang::t('hotbox', 'To');?></label>
						<?php echo $form->textField($model,'end',array('class'=>'from_time', 'id'=>'when-to', 'value'=>$model->end)); ?>
						<?php endif; ?>
						<div style="display: none" id="HotboxForm_end_em_" class="errorMessage"></div>
					</div>
				</div>
			</div>
			<div class="table-row row-editor">
				<label for=""><?php echo Lang::t('hotbox', 'Content') ?></label>
				<div class="table-cell-right">
					<?php echo $form->textArea($model,'body',array('style'=>'width: 398px; height: 130px;', 'class'=>'tinymce')); ?>
					<div style="display: none" id="HotboxForm_body_em_" class="errorMessage"></div>
				</div>
			</div>
			<!-- fake table row -->
			<div class="table-row row-photo">
				<label for=""><?php echo Lang::t('hotbox', 'Upload') ?></label>
				<div class="table-cell-right">
					<button class="but_upload" type="button"><?php echo Lang::t('hotbox', 'Upload') ?></button>
					<div style="display: none;">
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
										'onProgress'=>"js:function(id, name, loaded, total){ objCommon.loading(); }",
					                	'onComplete'=>"js:function(id, name, response){ hotbox.completeUpload(response, 'hotbox_thumbnail'); }",
					                    'onError'=>"js:function(id, name, errorReason){ hotbox.onError(id, name, errorReason); }",
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
				<div class="list_upload">
					<ul>
						<?php if(isset($model->images)) {?>
	                		<?php foreach ($model->images as $image) {?>
	                		<li><input type="hidden" value="<?php echo $image->id;?>" name="HotboxForm[tmp_images][]"><img width="100" height="100" src="<?php echo $image->getImageThumb(array(), true);?>" align="absmiddle"> <a class="del_upload" href="#" title="Delete photo"></a></li>
	                    	<?php } ?>
                    	<?php } ?>
					</ul>
				</div>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>