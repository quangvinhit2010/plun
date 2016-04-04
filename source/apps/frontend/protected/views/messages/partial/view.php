<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/messages/common.js', CClientScript::POS_BEGIN);
?>
	<ul class="message-list message-list-detail">
	    <?php $this->renderPartial('partial/_view-msg', array('msg'=>$model, 'read'=>true))?>
	<?php
	$answers = $model->getAnswers();
	if(!empty($answers)){
	    foreach ($answers as $answer){
	        $this->renderPartial('partial/_view-msg', array('msg'=>$answer, 'read'=>true));
	    }
	}
	?>
	</ul>
	<?php if($model->from_user->getDisplayName() != 'plunasia') {?>
		<div class="message-input">
		    <?php 
		    if($model->from_user->id == $this->usercurrent->id){
		        $msgUser = $model->to_user;
		    }else{
		        $msgUser = $model->from_user;
		    }
		    ?>
		    <?php $form=$this->beginWidget('CActiveForm', array(
			    'action' => $this->user->createUrl("//messages/reply"),
			    'htmlOptions' => array(
		        	'id' => 'replymsg-form'
		        )
			)); ?>
			<div class="textarea-wrap"><textarea name="body" class="replyMsg" placeholder="<?php echo Lang::t('messages', 'Write a reply...');?>"></textarea></div>
			<?php echo CHtml::hiddenField('answer_id', $model->id);?>
			<?php echo CHtml::hiddenField('from', $this->usercurrent->id);?>
			<?php echo CHtml::hiddenField('to', $msgUser->id);?>
			<?php echo CHtml::hiddenField('subject', 'Re: '.$model->title);?>
			<?php $this->endWidget();?>
		</div>
	<?php } ?>
