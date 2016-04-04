<?php 
if($model->from_user->id == $this->usercurrent->id){
	$msgUser = $model->to_user;
}else{
	$msgUser = $model->from_user;
}
$data_to = $msgUser->getAliasName();
$data_from = $this->usercurrent->getAliasName();

?>
<?php if(!empty($answers['next'])){?>
<p class="showmore"><a data-next="<?php echo $answers['next'];?>" data-url="<?php echo $this->usercurrent->createUrl("//messages/view");?>" data-key="<?php echo Util::encryptRandCode($model->id);?>" href="javascript:void(0);"><?php echo Lang::t('messages', 'View previous message')?></a></p>
<?php }?>
<ul class="list_message" data-to="<?php echo $data_to;?>" data-from="<?php echo $data_from;?>" data-gr="<?php echo md5($model->created);?>">
<?php
if(!empty($answers['data'])){
    foreach ($answers['data'] as $answer){
        $this->renderPartial('partial/_view-msg', array('msg'=>$answer, 'read'=>true));
    }
}
?>
</ul>
<?php if($model->from_user->getDisplayName() != 'plunasia') {?>
	<div class="message_input left">	    
	    <?php $form=$this->beginWidget('CActiveForm', array(
		    'action' => $this->user->createUrl("//messages/reply"),
		    'htmlOptions' => array(
	        	'id' => 'replymsg-form'
	        )
		)); ?>
		<div class="textarea_wrap">
			<textarea name="body" class="replyMsg" placeholder="<?php echo Lang::t('messages', 'Write a reply...');?>"></textarea>
		</div>
		<?php echo CHtml::hiddenField('answer_id', $model->id);?>
		<?php echo CHtml::hiddenField('from', $this->usercurrent->id);?>
		<?php echo CHtml::hiddenField('to', $msgUser->id);?>
		<?php echo CHtml::hiddenField('subject', 'Re: '.$model->title);?>
		<?php $this->endWidget();?>
	</div>
<?php } ?>
