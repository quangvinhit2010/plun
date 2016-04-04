<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/js/sys/invite.js"></script>	
<input id="url_invite" type="hidden" value="<?php echo Yii::app()->createUrl('/invitation/frontend/getFriends/connect_type') ?>" />
<a href="<?php echo Yii::app()->createUrl('/invitation/frontend/giftcode');?>">Giftcode</a>
<fieldset>
	<legend>Facebook</legend>
	<button openid="facebook" class="find_friends">Find friends</button>
	<div id="fb_friends">
		<ul class="friends">
		</ul>
		<div id="paging">
		</div>
	</div>
</fieldset>

<fieldset>
	<legend>Google</legend>
	<button openid="google" class="find_friends">Find friends</button>
	<div id="g_friends">
		<ul class="friends">
		</ul>
		<div id="paging">
		</div>
	</div>
</fieldset>

<div style="display: none;">
<div class="pop-invite">
	<div class="margin poplog">
		<div class="maintab clearfix">
			<div class="maintab-wrap clearfix">
				<div class="tab-top">
					<h2 class="poptitle-dangnhap">Đăng nhập bằng tài khoản LIKE ID</h2>
					<a href="javascript:sprPopClose();" title="Tắt" class="btn-close">Close</a>
				</div>
				<!-- top tab -->
				<div class="tab-cont">
					<div class="qckreg clearfix login">
						<?php $form = $this->beginWidget('CActiveForm', array(
							'id'=>'login-form',
							'action' => Yii::app()->createUrl("//site/ajaxLogin"),
							'enableAjaxValidation'=>true,
							'enableClientValidation'=>true,
							'clientOptions'=> array('validateOnSubmit'=>true),
							'focus'=>array($inviteForm,'username'),
							));
						?>
						<ul>
							<li>
								<?php echo $form->hiddenField($inviteForm,'email', array('class'=>'invite_email')); ?>
								<?php echo $form->hiddenField($inviteForm,'type', array('class'=>'invite_type')); ?>
							</li>
							<li>
								<?php 
								$inviteForm->message = 'Chào mừng bạn đến với Đông Phong';
								?>
								<?php echo $form->labelEx($inviteForm,'message'); ?>
								<?php echo $form->textArea($inviteForm,'message'); ?>
								<?php echo $form->error($inviteForm,'message'); ?>	
							</li>
							<li class="button-log">
								<?php echo CHtml::submitButton('Login', array('class'=>'btnInvite')); ?>
							</li>
							<li id="log-loading" style="margin-left: 145px;"></li>
						</ul>
						<?php $this->endWidget(); ?>										
						<!-- form -->
					</div>
					<!-- log in -->
				</div>
				<!-- tab top -->
			</div>
			<!-- main table -->
		</div>
		<!-- wrap -->
	</div>
</div>
</div>




<style type="text/css">	
	.friends{
		clear: both;
	}
	
	.friends li{
		float: left;
		margin: 3px;
		width: 300px;
	}
	
	.friends li img{
		width: 40px;
	}
	
	.paging{
		clear: both;
	}
	
	.paging li{
		float: left;
		padding: 2px;
	}
	
	.paging .selected{
		text-decoration: underline;
	}
</style>
