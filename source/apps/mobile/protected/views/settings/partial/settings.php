        <div class="profile_setting_page">
            <div class="left menu_setting">
            	<ul>
                	<li class="icon_ps active main_st profile_settings">
                    	<a href="javascript:void(0);"><label></label><?php echo Lang::t('settings', 'Profile Settings'); ?></a>
                        <ol style="display:block;" class="sub_main">
                        	<li class="active"><a href="javascript:void(0);" onclick="getBasicForm(this);" link="<?php echo $this->user->createUrl('/settings/getBasicInfo'); ?>"><?php echo Lang::t('settings', 'Basic Info'); ?></a></li>
                            <li><a href="javascript:void(0);" onclick="getWhatYouSeeForm(this);" link="<?php echo $this->user->createUrl('/settings/getWhatYouSee'); ?>"><?php echo Lang::t('settings', 'What you see'); ?></a></li>
                            <li><a href="javascript:void(0);" onclick="getExtraForm(this);" link="<?php echo $this->user->createUrl('/settings/getExtra'); ?>"><?php echo Lang::t('settings', 'Extra'); ?></a></li>
                        </ol>
                    </li>
                    <li class="icon_as main_st account_settings">
                    	<a href="javascript:void(0);"><label></label><?php echo Lang::t('settings', 'Account Settings'); ?></a>
                    	<ol style="display:block;" class="sub_main">
                        	<li><a href="javascript:void(0);" onclick="languageSettings(this);" link="<?php echo $this->user->createUrl('/settings/languageSettings'); ?>"><?php echo Lang::t('settings', 'Languages'); ?></a></li>
                            <li><a href="javascript:void(0);" onclick="getAccountSettings(this);" link="<?php echo $this->user->createUrl('/settings/getAccountSettings'); ?>"><?php echo Lang::t('settings', 'Change Email & Password'); ?></a></li>
                        </ol>
                    </li>
                </ul>
            </div>
            <div class="edit_profile_form">
	            <?php 
	            $this->renderPartial('partial/settings_basic', array(
	                    'model' => $model,
	                    'model_profile' => $model_profile,
	            		'list_country'	=>	$list_country,
	            		'list_state'	=>	$list_state,
	            		'list_city'	=>	$list_city,
	            		'list_district'	=>	$list_district
	            ));
	            ?>
	            <div class="left setting_col2">
	            </div>
            </div>
        </div>