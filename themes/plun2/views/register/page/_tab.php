<?php 
$controller = Yii::app()->controller->id;
$action = Yii::app()->controller->action->id;
$usercurrent = Yii::app()->user->data();
?>
<ul class="tab-step">
	<li<?php if(strtolower($action)=='stepupdateprofile'){?> class="active"<?php }?>><a href="javascript:void(0);" title=""><span class="num">1</span><span class="text"><?php echo Lang::t('register', 'Update Profile')?></span></a></li>
	<li<?php if(strtolower($action)=='stepavatar'){?> class="active"<?php }?>><a href="javascript:void(0);" title=""><span class="num">2</span><span class="text"><?php echo Lang::t('register', 'Set Avatar')?></span></a></li>
	<li<?php if(strtolower($action)=='stepfindfriends' ||strtolower($action) =='twittercallback'){?> class="active"<?php }?>><a href="javascript:void(0);" title=""><span class="num">3</span><span class="text"><?php echo Lang::t('register', 'Find Friends')?></span></a></li>
	<li<?php if(strtolower($action)=='stepsuggest'){?> class="active"<?php }?>><a href="javascript:void(0);" title=""><span class="num">4</span><span class="text"><?php echo Lang::t('register', 'Suggest Friends')?></span></a></li>
</ul>