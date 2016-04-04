<div class="pad_left_10 pad_top_10">                  
	<div class="left list_message">
    	<ul class="list_message_detail">
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
	</div>
	<?php if($model->from_user->getDisplayName() != 'plunasia') {?>
	<div class="type_message_detail">
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
		<textarea class="replyMsg" name="body" cols="" rows="" placeholder="<?php echo Lang::t('messages', 'Write a reply...');?>"></textarea>
		<input type="button" value="<?php echo Lang::t('messages', 'Send')?>" class="btnSend"/>
		<?php echo CHtml::hiddenField('answer_id', $model->id);?>
		<?php echo CHtml::hiddenField('from', $this->usercurrent->id);?>
		<?php echo CHtml::hiddenField('to', $msgUser->id);?>
		<?php echo CHtml::hiddenField('subject', 'Re: '.$model->title);?>
		<?php $this->endWidget();?>
	</div>
	<?php } ?>
</div>
