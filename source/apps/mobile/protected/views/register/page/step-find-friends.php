<div class="step-search">
	<?php $this->renderPartial('page/_tab');?>
	<div class="step-cont">
		<h2><?php echo Lang::t('register', 'Are your friends already On Plun?'); ?></h2>
		<p><?php echo Lang::t('register', 'Many of your friends may already be here. Searching your email account is the fastest way to find your friends on Plun.'); ?></p>
        
        <?php 
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/sys/invite.js', CClientScript::POS_BEGIN);
        ?>
        <input id="url_invite" type="hidden" value="<?php echo Yii::app()->createUrl('/invitation/frontend/getFriends/connect_type') ?>" />
		<div class="block-step">
			<ul class="search">
				<li>
					<i class="icon-yahoo"></i><span>Yahoo</span>
					<a class="find-friend" action="/invitation/frontend/GetFriendsYahoo" openid="yahoo" href="javascript:void(0);" title=""><?php echo Lang::t('register', 'Find Friends'); ?></a>		
					<div class="findfriend-yahoo-result" style="display: none;">
					</div>				
				</li>
				<li>
					<i class="icon-gmail"></i><span>Gmail</span>
					<a class="find-friend" action="/invitation/frontend/GetFriendsGoogle" openid="google" href="javascript:void(0);" title=""><?php echo Lang::t('register', 'Find Friends'); ?></a>
					<div class="findfriend-google-result" style="display: none;">
					</div>
				</li>
			</ul>
		</div>
	</div>
	<input type="hidden" name="getcontact_offset" id="getcontact_offset" value="<?php echo $limit;?>"/>
	<input type="hidden" name="friendslist_offset" id="friendslist_offset" value="<?php echo $limit;?>"/>
	<input type="hidden" name="standard_limit" id="standard_limit" value="<?php echo $limit;?>"/>
	
	<a class="back-step" href="<?php echo Yii::app()->createUrl('/register/stepAvatar');?>" title=""><i></i><?php echo Lang::t('general', 'Back'); ?></a>
	<a class="skip-step" href="<?php echo Yii::app()->createUrl('/register/stepSuggest');?>" title=""><?php echo Lang::t('register', 'Skip this Step'); ?><i></i></a>
</div>

<?php $this->widget('mobile.widgets.UserPage.PopupAlert', array('class'=>'find-friend', 'content'=>Lang::t('register', 'sent invitation email successfully!'))); ?>
