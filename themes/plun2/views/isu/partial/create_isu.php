<div class="popup_hotbox_event">
	<div class="title"><?php echo ($model->isNewRecord) ? Lang::t('isu', 'Create ISU') : Lang::t('isu', 'Edit') ?></div>
	<div class="content">
		<div class="hotbox-create isu-create">
			<?php
				$form=$this->beginWidget('CActiveForm', array(
					'id'=>'isu-form',
					'action' => ($model->isNewRecord) ? Yii::app()->createUrl('//isu/create') : Yii::app()->createUrl('//isu/edit', array('id' => $model->id)),	
					'enableAjaxValidation'=>false,
			  		'clientOptions'=>array(
			  				'validateOnSubmit'=>true,
			  				'afterValidate'=> 'js:isu.submit'
			  		),
					'htmlOptions'=>array(
						'class'=>'isu-form',
					),
				))
			?>
			<div class="table-row">
				<label for=""><?php echo Lang::t('isu', 'Title');?></label>
				<div class="table-cell-right">
					<div class="input-wrap">
						<?php echo $form->textField($model, 'title', array('placeholder'=>Lang::t('isu', 'Enter title...'))) ?>
						<div style="display: none;" id="Notes_title_em_" class="errorMessage"></div>
					</div>
				</div>
			</div>
			<div class="table-row where">
				<label for=""><?php echo Lang::t('hotbox', 'Where') ?></label>
				<div class="table-cell-right">
                    <div class="table-row">
                        <div class="input-wrap">
                            <?php
                                $list_country = CHtml::listData($list_country, 'id', 'name');
                                $top_country = CHtml::listData(SysCountry::model()->getCountryTop(), 'id', 'name');
                                $group_countrys =   array('----------- ' => $top_country, '-----------' => $list_country);

                                echo CHtml::dropDownList('Notes[country_id]', $model->country_id, $group_countrys, array('onchange' => 'getStateRegister();', 'name' => 'txt-country','id' => 'select-country','class' => 're-country virtual_form', 'text' => 'country_register_text'));
                            ?>
                            <?php
                                $display = $list_state ? '' : 'display: none';
                                $list_state = CHtml::listData($list_state, 'id', 'name');
                                echo CHtml::dropDownList('Notes[city_id]',$model->city_id, $list_state, array('style'=>$display, 'name' => 'txt-state', 'id' => 'select-state', 'onchange' => "getCityRegister();",'class' => 're-state virtual_form', 'text' => 'state_register_text','empty' => Lang::t('search', '--Any--')));
                            ?>
                            <?php
                                $display = sizeof($list_city) ? '' : 'display: none';
                                $list_city = CHtml::listData($list_city, 'id', 'name');
                                $city_select	=	isset($list_city[$model->city_id])	?	$list_city[$model->city_id]	:	Lang::t('search', '--Any--');
                                echo CHtml::dropDownList('Notes[district_id]',$model->district_id, $list_city, array('style'=>$display, 'name' => 'txt-city', 'id' => 'select-city','class' => 're-city virtual_form', 'text' => 'city_register_text', 'empty' => Lang::t('search', '--Any--')));
                            ?>
                        </div>
                    </div>
                    <div class="suggestLoactionCommon">
                        <span class="icon_common icon_loca"></span>
                        <input class="txtSearchLoaction" type="text" placeholder="add location" name="Notes[check_in_location_isu]" id="check_in_location_isu">
                       
                        <input type="hidden" name="Notes[isu_suggest_text_venue]" id="isu_suggest_text_venue" value="" />
						<input class="inputHiddenVenue" type="hidden" name="Notes[isu_suggest_id_venue]" id="isu_suggest_id_venue" value="0" />
                        
                        <span class="icon_common icon_dle"></span>
                        <div class="list_suggers_location" style="display:none;">
                            <ul class="clearfix">

                            </ul>
                        </div>
                        
                    </div>
					
				</div>
			</div>
			<div class="table-row when">
				<label for=""><?php echo Lang::t('hotbox', 'When') ?></label>
				<div class="table-cell-right">
					<div class="input-wrap">
						<?php if($model->isNewRecord): ?>
						<?php echo $form->textField($model,'date',array('class'=>'from_time', 'id'=>'when-from', 'value'=>date('d-m-Y h:i'))); ?>
						<label class="goto" for="when_to"><?php echo Lang::t('hotbox', 'To');?></label>
						<?php echo $form->textField($model,'end_date',array('class'=>'from_time', 'id'=>'when-to', 'value'=>date('d-m-Y h:i'))); ?>
						<?php else: ?>
						<?php echo $form->textField($model,'date',array('class'=>'from_time', 'id'=>'when-from', 'value'=>$model->start)); ?>
						<label class="goto" for="when_to"><?php echo Lang::t('hotbox', 'To');?></label>
						<?php echo $form->textField($model,'end_date',array('class'=>'from_time', 'id'=>'when-to', 'value'=>$model->end)); ?>
						<?php endif; ?>
						<div style="display: none" id="Notes_end_date_em_" class="errorMessage"></div>
					</div>
				</div>
			</div>
			<div class="table-row row-editor">
				<label for=""><?php echo Lang::t('hotbox', 'Content') ?></label>
				<div class="table-cell-right">
					<?php echo $form->textArea($model,'body',array('style'=>'width: 398px; height: 130px;','class'=>'tinymce')); ?>
					<div style="display: none" id="Notes_body_em_" class="errorMessage"></div>
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
	                		$limit = 1;
	                		$upload = ($model->isNewRecord) ? Yii::app()->createUrl('//isu/upload') : Yii::app()->createUrl('//isu/upload', array('id' => $model->id));
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
					                	'onComplete'=>"js:function(id, name, response){ isu.completeUpload(response, 'hotbox_thumbnail'); }",
					                    'onError'=>"js:function(id, name, errorReason){ isu.onError(id, name, errorReason); }",
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