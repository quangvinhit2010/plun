<div class="claim_white_party">
	<div class="overlay" style="position: absolute; width: 100%; height: 100%; z-index: 999; top: 0px; left: 0px; display: none;">
		<img style="width: 16px; height: 16px; margin-top: 10px; margin-left: 10px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/bx_loader.gif" />
	</div>
	<?php $this->beginWidget('CActiveForm', array( 'id'=>'white-party-manila-form', ));?>
	<a href="#" class="close_button"></a>
	<h3>CLAIM YOUR WHITE TICKET TO WHITE PARTY MANILA 2014</h3>
	<ul>
		<li>
			<span><?php echo CHtml::activeLabel($model, 'full_name') ?> *</span>
			<?php echo CHtml::activeTextField($model, 'full_name') ?>
			<label class="arrow"></label>
		</li>
		<li>
			<span><?php echo CHtml::activeLabel($model, 'phone') ?></span>
			<?php echo CHtml::activeTextField($model, 'phone') ?>
			<label class="arrow"></label>
		</li>
		<li>
			<span><?php echo CHtml::activeLabel($model, 'id_no') ?> *</span>
			<?php echo CHtml::activeTextField($model, 'id_no') ?>
			<label class="arrow"></label>
		</li>
	</ul>
	<div class="btn_claim left">
		<a class="btn submit" href="#">Claim</a><a class="btn cancel" href="#">Cancel</a>
	</div>
	<?php $this->endWidget(); ?>
</div>