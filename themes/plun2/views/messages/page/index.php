<?php
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/messages.js?t='.time(), CClientScript::POS_END);
	Yii::app()->clientScript->registerCss('photos_css', '
		.listConversation.hide {
			display: none;
		}
		.list_message .emo {
			vertical-align: middle;
			margin: 0px;
		}
	');
?>
<div class="container pheader min_max_1024 wrap_scroll clearfix hasBanner_160 messages_page">
	<div class="wrap-feed left sticky_column">
        <div class="shadow_top"></div>
		<div class="message_list">
			<div class="title">
				<h3 class="left"><?php echo Lang::t('messages', 'Message');?></h3>				                     
			</div>
			<div class="content">
				<?php CController::forward('/messages/conversation', false); ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="explore left box_margin_fixed sticky_column">
		<div class="list_explore message_send">
            <div class="shadow_top"></div>
			<div class="title">
				<h3 class="left"></h3>
				<!-- <a class="post_link right" href="javascript:void(0);"><?php echo Lang::t('messages', 'New message')?></a> -->
			</div>
			<div class="content">
				<div class="scroll-down"><?php echo Lang::t('javascript', 'Scroll down to see new message') ?></div>
				<div id="message-wrap" data-bottom="1">
					<div id="loadingOutside"><div class="loadingInside"></div></div>
					<ul class="list_message"></ul>
					<ul class="composing"></ul>
				</div>
				<div class="message_input left">	    
					<div class="textarea_wrap">
						<textarea placeholder="Write a reply..." class="replyMsg" name="body"></textarea>
					</div>
					<div class="emoticons-wrap" style="position: relative; width: 20px;"><div class="emoticons-item"><span class="emo emo-1"></span><span class="emo emo-2"></span><span class="emo emo-3"></span><span class="emo emo-4"></span><span class="emo emo-5"></span><span class="emo emo-6"></span><span class="emo emo-7"></span><span class="emo emo-8"></span><span class="emo emo-9"></span><span class="emo emo-10"></span><span class="emo emo-11"></span><span class="emo emo-12"></span><span class="emo emo-13"></span><span class="emo emo-14"></span><span class="emo emo-15"></span><span class="emo emo-16"></span><span class="emo emo-17"></span><span class="emo emo-18"></span><span class="emo emo-19"></span><span class="emo emo-20"></span><span class="emo emo-21"></span></div><span class="emoticons"></span></div>
				</div>
			</div>
		</div>
		<?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_160)); ?>
	</div>
</div>
<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'deleteConversation', 'content'=>'')); ?>