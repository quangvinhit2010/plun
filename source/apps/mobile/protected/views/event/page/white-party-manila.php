<div class="form-get-claim popup-common">
	<div class="wrap-popup">
		<a href="#" class="icon-common icon-back">&nbsp;</a>
		<div class="content-in">
			<div class="wrap-get-claim ">
				<h1>CLAIM YOUR WHITE TICKET TO WHITE PARTY MANILA 2014</h1>
				<?php $this->beginWidget('CActiveForm', array( 'id'=>'white-party-manila-form', 'htmlOptions'=>array('style'=>'position: relative;')));?>
                    	<?php echo CHtml::activeTextField($model, 'full_name', array('placeholder'=>$model->getAttributeLabel('full_name') . ' *'))?>
                    	<?php echo CHtml::activeTextField($model, 'phone', array('placeholder'=>$model->getAttributeLabel('phone')))?>
                    	<?php echo CHtml::activeTextField($model, 'id_no', array('placeholder'=>$model->getAttributeLabel('id_no')))?>
                        <div class="right group-btn">
                        <img class="submit-wait" style="width: 16px; height: 16px; vertical-align: middle; display: none;" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/images/graphics/bx_loader.gif" />
						<input type="submit" class="btn-common" value="Claim"> <a
						class="btn-common btn-cancel" href="#">Cancel</a>
						<div class="submit-wait" style="position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; display: none;"></div>
				</div>
				<div class="clear"></div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>