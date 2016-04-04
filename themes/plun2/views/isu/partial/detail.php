<div class="poster_isu">
	<a target="_blank" class="left avatar" href="<?php echo $isu->user->getUserUrl() ?>"><img src="<?php echo $isu->user->getAvatar() ?>" align="absmiddle" width="50" height="50" /></a>
	<div class="left nick">
		<p><a href="#"><b><?php echo $isu->user->getUserLink(array('target' => '_blank')) ?></b></a></p>
		<p class="time"><?php echo Util::getElapsedTime($isu->created) ?></p>
	</div>
	<div class="right link_reply_forward">
		<ul>
			<?php if($isu->user_id != Yii::app()->user->id){ ?>
			<li><a data-username="<?php echo $isu->user->username ?>" class="reply" href="#"><ins></ins><?php echo Lang::t('isu', 'Reply') ?></a></li>
			<?php } ?>
			<li><a id="opener_forward" class="forward" href="#"><ins></ins><?php echo Lang::t('isu', 'Forward') ?></a></li>
		</ul>
	</div>
</div>
<div class="wrap_scroll_popup">

<div class="detail_isu">
	<h3><?php echo $isu->title ?></h3>
	<div class="post_time">
		<p><b><?php echo Lang::t('isu', 'About') ?>:</b> <?php echo date('Y-m-d H:i', $isu->date) ?> <b class="to_time"><?php echo Lang::t('isu', 'To') ?>:</b> <?php echo date('Y-m-d H:i', $isu->end_date) ?></p>
		<p><b><?php echo Lang::t('isu', 'Location') ?>:</b> <?php echo $isu->getLocation() ?></p>
					            <?php if($isu->venue_id): 
            $url	=	Yii::app()->createUrl('venues/getVenueDetail', array('venue_id' => $isu->venue->id));
            ?>
            <p><b><?php echo Lang::t('venue', 'At') ?>:</b> <a class="popupListCheckIn" href="<?php echo $url; ?>"><?php echo $isu->venue->title; ?></a></p>
            <?php endif; ?>
	</div>
	<div class="content">
		<?php echo $isu->body ?> 		
	</div>
</div>   
</div>
<div style="display: none;">
	<div class="popup_general popup_isu_forward ui-dialog-content ui-widget-content" style="max-width: 1920px; width: auto; min-height: 0px; max-height: none; height: 216px;" id="ui-id-3">
        <div class="title"><?php echo Lang::t('isu', 'Forward this ISU to friend\'s') ?></div>
        <div class="content">
        	<input type="hidden" id="reply-url" value="<?php echo Yii::app()->createUrl('/isu/send') ?>" />
        	<?php
	        	$model = new MessageForm();
	        	$model->to = $isu->user->getDisplayName();
	        	$form=$this->beginWidget('CActiveForm', array(
					'id'=>'forward-isu-form',
					'action'=>Yii::app()->createUrl('/isu/send', array('type'=>'forward', 'isu_id' => $isu->id)),
					'enableAjaxValidation'=>false,
					'enableClientValidation'=>true,
					'clientOptions'=>array(
					'validateOnSubmit'=>true,
					'afterValidate'=> 'js:ISU.forward_submit'
				),))
			?>
			<div class="field-wrap">
            	<?php echo $form->textField($model,'to', array('value'=>'','placeholder'=> Lang::t('isu', 'Enter name to forward...'))); ?>
            	<div style="display: none;" id="MessageForm_to_em_" class="errorMessage"></div>         		        
            </div>
            <div class="field-wrap">
				<?php echo $form->textArea($model,'body', array('placeholder'=> Lang::t('isu', 'Enter text...'))); ?>
            	<div style="display: none;" id="MessageForm_subject_em_" class="errorMessage"></div>
            </div>
            <?php echo $form->hiddenField($model,'from',array('value'=>Yii::app()->user->data()->alias_name)); ?>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>