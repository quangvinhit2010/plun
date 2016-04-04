<div class="comment_list">
	<ol>
		<?php
		$object_id = 63072;
		$type_id = Comment::COMMENT_ACTIVITY;
		$fcomment = Comment::model()->getComments($object_id, $type_id);
		if ($fcomment) {
			$params['data'] = $fcomment;
			$params['user'] = $this->user;
			$params['usercurrent'] = $usercurrent;
			////$params['isLogged'] = $isLogged;
			$params['config'] = $config;
			$params['object_id'] = $object_id;
			$params['type_id'] = $type_id;
			$params['object'] = $dataobj;
			$params['usercurrent'] = $usercurrent;
				
			$this->renderPartial("//photo/partial/action/comment-list", $params);
		} 
		?>
	</ol>
</div>

<?php if (!empty($usercurrent)): 
	$model = new Comment();
?>				
	<div class="txt_cmt_box">
	    	<div class="avatar_feed left"><a href="<?php echo $usercurrent->getUserUrl();?>"><img width="35px" height="35px" src="<?php echo $usercurrent->getAvatar() ?>" alt="" border=""/></a></div>
	        <?php $form = $this->beginWidget('CActiveForm', array(
					'action' => $usercurrent->createUrl("//newsFeed/commentActivity"),
					'htmlOptions' => array(
						'class' => 'comment-form'
					)
				));
			?>
	        <div class="comment_feed loadingItem">
				<?php echo $form->hiddenField($model, 'item', array('value' => Util::encrypt(CJSON::encode(array('item_id'=>'', 'type'=>''))))); ?>
				<?php echo $form->textArea($model, 'content', array('class' => 'cmt-post-text expand', 'placeholder' => 'Write a comment...', 'id'=>'')); ?>
	        </div>
	        <?php $this->endWidget(); ?>
	</div>
<?php endif; ?>