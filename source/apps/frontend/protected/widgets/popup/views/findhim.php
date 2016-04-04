<?php
$cs = Yii::app()->clientScript;
/*$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/search.js', CClientScript::POS_BEGIN);*/
?>    		
	<div id="pop-find-him" class="find_him_pop popup find-him-pop">
		<div class="arrow_tab"></div>
		<div class="btn-close-new"></div>
		 
    	<form action="javascript:void(0);" id="reference-search-from" class="frm-location" method="post" onsubmit="return action_find_location();">	
        
        <div class="tab-cont reference-cont">
                    <ul>
                        <li>
                            <label for="txt-country"><?php echo Lang::t('settings', 'Country'); ?>:</label>
                            <div class="select_style w120">
                                <?php
                                $html_option		=	false;
                                $html_listcountry		=	'';
                                if ($country_id) {
                                		
                                	$top_country 			= 	SysCountry::model()->getCountryTop();
                                		
                                	if(isset($top_country[$country_id])){
                                		$selected_text		=	$top_country[$country_id]['name'];
                                		$selected_top_id	=	$country_id;
                                		$selected_id		=	false;
                                	}else{
                                		$selected_text		=	isset($list_country[$country_id]['name'])	?	$list_country[$country_id]['name']	:	Lang::t('search', '--Any--');
                                		$selected_top_id	=	false;
                                		$selected_id		=	!empty($country_id)	?	$country_id	:	false;
                                	}
                                	$list_country = CHtml::listData($list_country, 'id', 'name');
                                		
                                	if(sizeof($top_country)){
                                		array_unshift($top_country, array('id' => 0, 'name' => Lang::t('search', 'Global')));
                                		$top_country 			= 	CHtml::listData($top_country, 'id', 'name');
                                		$html_listcountry		=	CHtml::listOptions($selected_top_id, array('------------' => $top_country), $html_option);
                                	}
                                		
                                	$html_listcountry		=	$html_listcountry	.	CHtml::listOptions($selected_id, array('------------' => $list_country), $html_option);
                                		
                                } else {
                                	$html_listcountry		=	CHtml::listOptions($country_id, $list_country, $html_option);
                                }
                                
                                ?>
								<select text="country_findhim_text" class="findhim-country virtual_form" id="country_id" name="country_id" onchange="Location.getStateListFindHim();">
									<?php echo $html_listcountry; ?>
								</select>                                
                                
                                <span class="txt_select"><span class="country_findhim_text"><?php echo $selected_text; ?></span></span> <span class="btn_combo_down"></span>
                            </div>									                        
                            <?php
                            if ($list_state) {
                                $state_none = '';
                            } else {
                                $state_none = ' style="display: none;"';
                            }
                            ?>
                            <div class="fh-row-state-contain"<?php echo $state_none; ?>>                           
                                <div class="select_style w120 fh-row-state">
                                <?php if ($list_state): ?>
                                    <?php
									                                    $html_option		=	false;
									                                    $html_liststate		=	'';
									                                    
									                                    if ($country_id) {

									                                        $top_state 			= 	LocationState::model()->getTopStateByCountry($country_id);
									                                        

									                                        $list_state = CHtml::listData($list_state, 'id', 'name');
									                                        
									                                        if(sizeof($top_state)){
																				$top_state 			= 	CHtml::listData($top_state, 'id', 'name');
									                                        	$html_liststate		=	CHtml::listOptions(false, array('------------' => $top_state), $html_option);
									                                        }
									                                        
									                                        $html_liststate		=	$html_liststate	.	CHtml::listOptions(false, array('------------' => $list_state), $html_option);
									                                        $html_liststate		=	CHtml::listOptions(false, array(Lang::t('search', '--Any--')), $html_option)	.	$html_liststate;
									                                        
									                                    } else {
									                                        $html_liststate		=	CHtml::listOptions(false, $list_state, $html_option);
									                                    }
                                    ?>  
                                    <select text="state_findhim_text" class="select-type-1 fh-state virtual_form" id="findhim-state" name="state_id" onchange="Location.getCityListFindHim();">
										<?php echo $html_liststate; ?>
									</select>
                                    <span class="txt_select"><span class="state_findhim_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
                                 <?php endif; ?>
                                </div> 
                            </div>
                        </li>         
                        <li style="display: none;" class="fh-row-citydistrict-contain">
                            <label>&nbsp;</label>                       	
                            <div class="select_style w120 fh-row-city select-type-5" style="display: none;">
                            	<select text="city_findhim_text" class="select-type-1 fh-city virtual_form" id="findhim-city" name="city_id" onchange="Location.getDistrictListFindHim();">
                            	</select>
                            	<span class="txt_select"><span class="city_findhim_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
                            </div>                                                    
                            <div class="select_style w120 fh-row-district select-type-5" style="display: none;">
                             	<select text="district_findhim_text" class="select-type-1 fh-district virtual_form" id="findhim-district" name="district_id" />
                            	</select>
                            	<span class="txt_select"><span class="district_findhim_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>                           
                            </div>
                        </li>
                        <li class="bor_bot"></li>
                        <li class="frm_marpa">
                            <label><?php echo Lang::t('search', 'Age'); ?>:</label>
                            <input name="text-age-from" id="txt-age-from" type="text" class="input-type-2 input_setting w60" placeholder="<?php echo Lang::t('search', 'From'); ?>">
                            <input name="text-age-to" id="txt-age-to" type="text" class="input-type-2 input_setting w60" placeholder="<?php echo Lang::t('search', 'To'); ?>">
                        </li>
                        <li>
                            <label><?php echo Lang::t('settings', 'Sexuality'); ?>:</label>
                            <div class="findhim-sexrole findhim-looking-for">
                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXUALITY_GAY; ?>" name="sexuality" id="sexuality-gay" class="input-type-3 sexuality">
                                    <label for="sexuality-gay"></label>
                                    <label for="sexuality-gay" class="mar_left_24"><?php echo Lang::t('settings', 'Gay'); ?></label>
                                </div>		

                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXUALITY_LESBIAN; ?>" name="sexuality" id="sexuality-lesbian" class="input-type-3 sexuality">
                                    <label for="sexuality-lesbian"></label>
                                    <label for="sexuality-lesbian" class="mar_left_24"><?php echo Lang::t('settings', 'Lesbian'); ?></label>
                                </div>

                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXUALITY_BISEXUAL; ?>" name="sexuality" id="sexuality-bisexual" class="input-type-3 sexuality">
                                    <label for="sexuality-bisexual"></label>
                                    <label for="sexuality-bisexual" class="mar_left_24"><?php echo Lang::t('settings', 'Bisexual'); ?></label>
                                </div>

                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXUALITY_CURIOUS; ?>" name="sexuality" id="sexuality-curious" class="input-type-3 sexuality">
                                    <label for="sexuality-curious"></label>
                                    <label for="sexuality-curious" class="mar_left_24"><?php echo Lang::t('settings', 'Curious'); ?></label>
                                </div>

                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXUALITY_STRAIGHT; ?>" name="sexuality" id="sexuality-straight" class="input-type-3 sexuality">
                                    <label for="sexuality-straight"></label>
                                    <label for="sexuality-straight" class="mar_left_24"><?php echo Lang::t('settings', 'Straight'); ?></label>
                                </div>
                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXUALITY_TRANSGENDER; ?>" name="sexuality" id="sexuality-transgender" class="input-type-3 sexuality">
                                    <label for="sexuality-transgender"></label>
                                    <label for="sexuality-transgender" class="mar_left_24"><?php echo Lang::t('settings', 'Transgender'); ?></label>
                                </div>                                
                            </div> 								                        
                        </li>                        
                        <li>
                            <label><?php echo Lang::t('settings', 'Role'); ?>:</label>
                            <div class="findhim-sexrole findhim-looking-for">
                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXROLE_TOP; ?>" name="sex-role" id="sex-role-top" class="input-type-3 sex-role">
                                    <label for="sex-role-top"></label>
                                    <label for="sex-role-top" class="mar_left_24"><?php echo Lang::t('settings', 'Top'); ?></label>
                                </div>		

                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXROLE_BOTTOM; ?>" name="sex-role" id="sex-role-bottom" class="input-type-3 sex-role">
                                    <label for="sex-role-bottom"></label>
                                    <label for="sex-role-bottom" class="mar_left_24"><?php echo Lang::t('settings', 'Bottom'); ?></label>
                                </div>

                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXROLE_VERSATILE; ?>" name="sex-role" id="sex-role-versatile" class="input-type-3 sex-role">
                                    <label for="sex-role-versatile"></label>
                                    <label for="sex-role-versatile" class="mar_left_24"><?php echo Lang::t('settings', 'Versatile'); ?></label>
                                </div>

                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXROLE_TOP_VERSATILE; ?>" name="sex-role" id="sex-role-top-versatile" class="input-type-3 sex-role">
                                    <label for="sex-role-top-versatile"></label>
                                    <label for="sex-role-top-versatile" class="mar_left_24"><?php echo Lang::t('settings', 'Top/Versatile'); ?></label>
                                </div>

                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::SEXROLE_BOTTOM_VERSATILE; ?>" name="sex-role" id="sex-role-bottom-versatile" class="input-type-3 sex-role">
                                    <label for="sex-role-bottom-versatile"></label>
                                    <label for="sex-role-bottom-versatile" class="mar_left_24"><?php echo Lang::t('settings', 'Bottom/Versatile'); ?></label>
                                </div>
                            </div> 								                        
                        </li>
                        <li>
                            <label><?php echo Lang::t('settings', 'Looking for'); ?>:</label>
                            <div class="findhim-looking-for findhim-looking-for">
                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::LOOKINGFOR_FRIENDSHIP; ?>" name="looking-for" id="looking-for-friendship" class="input-type-3 looking-for">
                                    <label for="looking-for-friendship"></label>
                                    <label for="looking-for-friendship" class="mar_left_24"><?php echo Lang::t('settings', 'Friendship'); ?></label>
                                </div>
                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::LOOKINGFOR_RELATIONSHIP; ?>" name="looking-for" id="looking-for-relationship" class="input-type-3 looking-for">
                                    <label for="looking-for-relationship"></label>
                                    <label for="looking-for-relationship" class="mar_left_24"><?php echo Lang::t('settings', 'Relationship'); ?></label>
                                </div>
                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::LOOKINGFOR_SEX; ?>" name="looking-for" id="looking-for-sex" class="input-type-3 looking-for">
                                    <label for="looking-for-sex"></label>
                                    <label for="looking-for-sex" class="mar_left_24"><?php echo Lang::t('settings', 'Sex'); ?></label>
                                </div>
                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::LOOKINGFOR_CHAT; ?>" name="looking-for" id="looking-for-chat" class="input-type-3 looking-for">
                                    <label for="looking-for-chat"></label>
                                    <label for="looking-for-chat" class="mar_left_24"><?php echo Lang::t('settings', 'Chat'); ?></label>
                                </div>
                                <div class="squaredCheck">
                                    <input type="checkbox" value="<?php echo ProfileSettingsConst::LOOKINGFOR_HANGOUT; ?>" name="looking-for" id="looking-for-hangout" class="input-type-3 looking-for">
                                    <label for="looking-for-hangout"></label>
                                    <label for="looking-for-hangout" class="mar_left_24"><?php echo Lang::t('settings', 'Hangout'); ?></label>
                                </div>
                            </div>
                        </li>
                        <li class="frm_marpa">
                            <label><?php echo Lang::t('search', 'Weight'); ?>:</label>
                            <input name="weight-from" id="weight-from" type="text" class="input-type-2 input_setting w60" placeholder="<?php echo Lang::t('search', 'From'); ?>">							                        
                            <input name="weight-to" id="weight-to" type="text" class="input-type-2 input_setting w60" placeholder="<?php echo Lang::t('search', 'To'); ?>">
                            <div class="select_style w60">
                                <?php
                                $unit_weight	=	array(UsrProfileSettings::VN_UNIT => 'kg', UsrProfileSettings::EN_UNIT => 'lbs');
                                echo CHtml::dropDownList('weight-from-unit', $measurement, $unit_weight, array('id' => 'txt-weight-unit', 'class' => 'select-type-3 virtual_form', 'text' => 'weight_text'));
                                ?>
                                <span class="txt_select"><span class="weight_text"><?php echo $unit_weight[$measurement]; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                        </li>
                        <li class="frm_marpa">
                            <label><?php echo Lang::t('search', 'Height'); ?>:</label>
                            <input name="height-from" id="height-from" type="text" class="input-type-2 input_setting w60" placeholder="<?php echo Lang::t('search', 'From'); ?>">
                            <input name="height-to" id="height-to" type="text" class="input-type-2 input_setting w60" placeholder="<?php echo Lang::t('search', 'To'); ?>">
                            <div class="select_style w60">
								<?php
									$unit_height	=	array(UsrProfileSettings::VN_UNIT => 'cm', UsrProfileSettings::EN_UNIT => 'ft');
									echo CHtml::dropDownList('height-to-unit', $measurement, $unit_height, array('id' => 'txt-height-unit', 'class' => 'select-type-3 virtual_form', 'text' => 'height_text'));
								?>
                                <span class="txt_select"><span class="height_text"><?php echo $unit_height[$measurement]; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                        </li>
                        <li class="frm_marpa">
                            <label class="relationship"><?php echo Lang::t('settings', 'Relationship Status'); ?>:</label>
                            <div class="select_style w120">
<?php
$relationship = ProfileSettingsConst::getRelationshipLabel();
echo CHtml::dropDownList('txt-relationship', false, $relationship, array('name' => 'txt-relationship', 'id' => 'txt-relationship', 'class' => 'select-type-5 virtual_form', 'text' => 'relationship_text', 'prompt' => ProfileSettingsConst::EMPTY_DATA));
?>
                                <span class="txt_select"><span class="relationship_text"><?php echo ProfileSettingsConst::EMPTY_DATA; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                            <label><?php echo Lang::t('settings', 'Build'); ?>:</label>
                            <div class="select_style w120">
<?php
$build = ProfileSettingsConst::getBuildLabel();
echo CHtml::dropDownList('txt-body', false, $build, array('name' => 'txt-body', 'id' => 'txt-body', 'class' => 'select-type-5 virtual_form', 'text' => 'body_text', 'prompt' => ProfileSettingsConst::EMPTY_DATA));
?>
                                <span class="txt_select"><span class="body_text"><?php echo ProfileSettingsConst::EMPTY_DATA; ?></span></span> <span class="btn_combo_down"></span>
                            </div>                      
                        </li>
                        <li class="frm_marpa">
                            <label class="safesex"><?php echo Lang::t('settings', 'Safe sex'); ?>:</label>
                            <div class="select_style w120">
<?php
$safe_sex = ProfileSettingsConst::getSafeSexLabel();
echo CHtml::dropDownList('txt-safe-sex', false, $safe_sex, array('name' => 'txt-safe-sex', 'id' => 'txt-safe-sex', 'class' => 'select-type-5 virtual_form', 'text' => 'safe_sex_text', 'prompt' => ProfileSettingsConst::EMPTY_DATA));
?>
                                <span class="txt_select"><span class="safe_sex_text"><?php echo ProfileSettingsConst::EMPTY_DATA; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                            <label><?php echo Lang::t('settings', 'Ethnicity'); ?>:</label>
                            <div class="select_style w120">
<?php
$ethnicity = ProfileSettingsConst::getEthnicityLabel();
echo CHtml::dropDownList('txt-ethnics', false, $ethnicity, array('name' => 'txt-ethnics', 'id' => 'txt-ethnics', 'class' => 'select-type-5 virtual_form', 'text' => 'ethnicity_text', 'prompt' => ProfileSettingsConst::EMPTY_DATA));
?>
                                <span class="txt_select"><span class="ethnicity_text"><?php echo ProfileSettingsConst::EMPTY_DATA; ?></span></span> <span class="btn_combo_down"></span>
                            </div>                      </li>
                        <li class="bor_bot"></li>
                    </ul>
                    <div class="squaredCheck with_profile_pic">
                        <input type="checkbox" value="1" name="with_profile_pic" id="with_profile_pic" class="input-type-3 with_profile_pic" checked="checked">
                        <label for="with_profile_pic"></label>
                        <label for="with_profile_pic" class="mar_left_24"><?php echo Lang::t('search', 'With Profile Picture'); ?></label>
                    </div>
                    <button class="btn btn-white"><?php echo Lang::t('search', 'Find'); ?></button>
                </form>
        </div>
        <!--end pop-cont--> 
    </div>


