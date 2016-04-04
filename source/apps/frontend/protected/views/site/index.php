<?php Yii::app()->clientScript->registerScript('login-register', "
	var loginUrl = '".Yii::app()->createUrl('//site/login')."';
	var registerUrl = '".Yii::app()->createUrl('//register')."';
", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/home/common.js', CClientScript::POS_BEGIN);
setcookie("PHPSESSID", "", time() - 6400);
?>
<div class="home-left">
    <div class="wrap" id ="triquiback">
        <img id="triquibackimg" border="" alt="" src="/uploads/background/newBG.jpg">
        <div class="content-wrap">
        	<div><h3>find and connect with</h3></div>
            <div><h2>your dream boys</h2></div>
        	<?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
			)); ?>
            <div class="sign_in_up">
                <ul>
                    <li>
					    <div class="input-wrap<?php echo ($model->hasErrors('username')) ? ' error' : '';?>">
					        <?php echo $form->textField($model,'username', array('placeholder'=> Lang::t('login', 'Username'), 'class'=>'input_txt_username')); ?>
					        <label class="arrow"></label>
					        <?php echo $form->error($model,'username'); ?>
					    </div>
					</li>
					<li>
					    <div class="input-wrap<?php echo ($model->hasErrors('password')) ? ' error' : '';?>">
					        <?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('login', 'Password'), 'class'=>'input_txt_pass')); ?>
					        <label class="arrow"></label>
					        <?php echo $form->error($model,'password'); ?>
					    </div>
					</li>
					<li>
					    <?php echo $form->checkBox($model,'rememberMe'); ?> <label for="LoginForm_rememberMe"><?php echo Lang::t('login', 'Remember me');?></label>   |   <a href="<?php echo Yii::app()->createUrl('//site/forgotpass')?>"><?php echo Lang::t('login', 'Forgot your Password?');?></a>
					    <?php echo $form->error($model,'rememberMe'); ?>
					</li>
                </ul>
            </div>
            
            <div class="buttons">
                <?php echo CHtml::submitButton(Lang::t('login', 'Sign in'), array('class'=>'btn btn-gray signin')); ?>
                <button type="reset" class="btn btn-gray signup" onclick="window.location.href='<?php echo Yii::app()->createUrl('//register');?>'"><?php echo Lang::t('login', 'Sign up now');?></button>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<!-- home left -->
<div class="home-right scroll_avatar_home">
    <div class="wrap">
        <?php 
        $userTop = UserTop::model()->getUsers();
        if(!empty($userTop)){
            $city_in_cache = new CityonCache();
            $country_in_cache   =   new CountryonCache();
            $state_in_cache	=	new StateonCache();
            $city_info = $city_in_cache->getListCity();
            $country_info = $country_in_cache->getListCountry();
            $state_info = $state_in_cache->getListState();
        ?>
        <ul>
            <?php foreach ($userTop as $item){?>
            <?php 
            if(!empty($item->user)){
                $profileLocation = $item->user->profile_location;
                
                $location_display	=	'';
                $state_name   =   !empty($state_info[$profileLocation->current_state_id]['name'])  ?  $state_info[$profileLocation->current_state_id]['name']    :   '' ;
                $country_name   =   !empty($country_info[$profileLocation->current_country_id]['name'])   ?   $country_info[$profileLocation->current_country_id]['name']    :   '';
                if(!empty($country_name)){
                    if(!empty($state_name)){
                        $location_display	=	"$state_name, $country_name";
                    }else{
                        $location_display	=	$country_name;
                    }
                }
                $sex_roles_title	=	ProfileSettingsConst::getSexRoleLabel();
                if(isset($item->user->profile_settings) && is_object($item->user->profile_settings)){
                    $sex_role = isset($sex_roles_title[$item->user->profile_settings->sex_role]) ? $sex_roles_title[$item->user->profile_settings->sex_role] : '';
                }else{
                    $sex_role  =   '';
                }
                
                $birthday_year   =   isset($item->user->profile_settings->birthday_year)  ?  $item->user->profile_settings->birthday_year    :   false ;
                ?>
                <li>
                    <a href="<?php echo $item->user->getUserUrl();?>" title="" class="ava">
                        <img src="<?php echo $item->user->getAvatar();?>" alt="" border="">
                        <span class="ava-bg"></span>
                        <div class="name">
                            <span class="fname"><?php echo $item->user->getDisplayName();?></span>
                            <div class="more">
                                <p class="location">
                                    <i class="imed imed-loc"></i>
                                    <span class="text"><?php echo $location_display;?></span>
                                </p>
                                <?php if($birthday_year): ?>
                                    <p class="intro"><?php echo Lang::t('search', 'Age'); ?>: <?php echo date('Y') - $birthday_year; ?></p>
                                <?php endif; ?>
                                <p class="contact"><?php echo $sex_role; ?></p>
                            </div>
                        </div>
                    </a>
                </li>
            <?php }?>
            <?php }?>
        </ul>
        <?php 
        }
        ?>
    </div>
</div>
<!-- home right -->