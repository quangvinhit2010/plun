<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/common.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/location/common.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/newsfeed/common.js');
$limitViewProfileInDay = Yii::app()->user->getFlash('limitViewProfileInDay');

if(!empty($limitViewProfileInDay) && $limitViewProfileInDay == true){
	Yii::app()->clientScript->registerScript('newsfeed', "NewsFeed.limitViewProfileInDay(\"".Lang::t('general', 'Sorry! You have exceeded your daily profile view limit of 30.')."\", 400);", CClientScript::POS_END);
	Yii::app()->user->setFlash('limitViewProfileInDay', false);
}
?>
					<div class="feed_explore">
                    	<ul>
                        	<li class="active mar_rig_10"><a href="/"><?php echo Lang::t('general', 'Explore'); ?></a></li>
                            <li class=""><a href="<?php echo $this->usercurrent->createUrl('newsFeed/feed')?>"><?php echo Lang::t('general', 'Feeds'); ?></a></li>
                        </ul>
                    </div>
                    <div class="pad_left_10 pad_top_10 search_result">
                    		<p class="left total_member"></p>
                            <ul class="list_user quicksearch">
                             </ul>
                    </div>
                    <!-- popup search -->
						<div class="pad_left_10 pad_top_10 popup_search_location" style="display: none;">
						  <form action="javascript:void(0);" id="reference-search-from" class="frm-location" method="post" onsubmit="return action_find_location();">
						  <p class="left total_member"><?php echo Lang::t('search', 'Search members'); ?></p>
						  <div class="left search_member">
						    <div class="country">
						      <p class="title"><?php echo Lang::t('settings', 'Country'); ?></p>
						      <div class="left">
						        <ul class="quicksearch">
						          <li>
		                            <div class="select_style w120">
		                                <?php
		                                $list_country = CHtml::listData($list_country, 'id', 'name');
		                                $top_country	=	array();
		                                $top_country[]	=	array('id' => 0, 'name' => Lang::t('search', 'Global'));
		                                $top_country1 = SysCountry::model()->getCountryTop();
		                                
		                                foreach($top_country1 AS $row){
		                                	array_push($top_country, $row);
		                                }	                                
		                                $top_country = CHtml::listData($top_country, 'id', 'name');
		                                if(isset($top_country[$country_id])){
											$selected_text	=	$top_country[$country_id];
		                                	unset($list_country[$country_id]);
		                                }else{
		                                	$selected_text	=	$list_country[$country_id];
		                                }
		                                $list_country_group = array('----------- ' => $top_country, '-----------' => $list_country);
		
		                                echo CHtml::dropDownList('country_id', $country_id, $list_country_group, array('onchange' => 'getStateFindHim();', 'name' => 'txt-country', 'id' => 'findhim-country', 'class' => 'select-type-1 fh-country virtual_form', 'text' => 'country_findhim_text'));
		                                ?>
		                                <span class="txt_select"><span class="country_findhim_text"><?php echo $selected_text; ?></span></span> <span class="btn_combo_down"></span>
		                            </div>
						          </li>
								  <?php
		                            if ($list_state) {
		                                $state_none = '';
		                            } else {
		                                $state_none = ' style="display: none;"';
		                            }
		                          ?>
						          <li class="fh-row-state-contain"<?php echo $state_none; ?>>
						            <div class="select_style">
		                                <?php if ($list_state): ?>
		                                    <?php
		                                    $list_state = CHtml::listData($list_state, 'id', 'name');
		                                    if ($country_id) {
		                                        $top_state = LocationState::model()->getTopStateByCountry($country_id);
		                                        $top_state = CHtml::listData($top_state, 'id', 'name');
		                                        
		                                        if (sizeof($top_state)) {
		                                            $list_state_group = array(' ' => $top_state, '------------' => $list_state);
		                                        } else {
		                                            $list_state_group = $list_state;
		                                        }
		                                    } else {
		                                        $list_state_group = $list_state;
		                                    }
		                                    echo CHtml::dropDownList('state_id', 0, $list_state_group, array('name' => 'txt-state', 'id' => 'findhim-state', 'onchange' => "getCityFindHim();", 'class' => 'select-type-1 fh-state virtual_form', 'text' => 'state_findhim_text', 'empty' => Lang::t('search', '--Any--')));
		                                    ?>  
		                                    <span class="txt_select"><span class="state_findhim_text"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
		                                 <?php endif; ?>
		                             </div>
						          </li>
						          <li class="fh-row-city-contain" style="display: none;">
						          	<div class="select_style"></div>
						          </li>
						          <li class="fh-row-district-contain" style="display: none;">
						          	<div class="select_style"></div>
						          </li>						          
						        </ul>
						      </div>
						    </div>
						    <div class="left other_info">
						      <ul>
						        <li>
						          <label class="title"><?php echo Lang::t('search', 'Age'); ?>:</label>
						          <div class="mar_lef_20">
						            <input type="text" placeholder="From" class="input-type-2 input_setting w130" id="txt-age-from" name="text-age-from">
						            <input type="text" placeholder="To" class="input-type-2 input_setting w130" id="txt-age-to" name="txt-age-to">
						          </div>
						        </li>
						        <li>
						          <label class="title"><?php echo Lang::t('settings', 'Role'); ?>:</label>
						          <div class="findhim-sexrole mar_lef_20">
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
						          <label class="title"><?php echo Lang::t('settings', 'Looking for'); ?>:</label>
						          <div class="mar_lef_20">
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
						        <li>
						          <label class="title"><?php echo Lang::t('search', 'Weight'); ?>:</label>
						          <div class="mar_lef_20">
						            <input type="text" placeholder="<?php echo Lang::t('search', 'From'); ?>" class="input-type-2 input_setting w70 left" id="weight-from" name="weight-from">
						            <div class="select_style w70">
                                		<?php
                              	 			echo CHtml::dropDownList('weight-from-unit', false, array('0' => 'kg', '1' => 'pound'), array('id' => 'txt-weight-from', 'class' => 'select-type-3 virtual_form', 'text' => 'weight_from_text'));
                                		?>
                                		<span class="txt_select"><span class="weight_from_text">kg</span></span> <span class="btn_combo_down"></span> 
						            </div>
						            <input type="text" placeholder="<?php echo Lang::t('search', 'To'); ?>" class="input-type-2 input_setting w70 left" id="weight-to" name="weight-to">
						            <div class="select_style w70">
                                		<?php
                              	 			echo CHtml::dropDownList('weight-to-unit', false, array('0' => 'kg', '1' => 'pound'), array('id' => 'txt-weight-to', 'class' => 'select-type-3 virtual_form', 'text' => 'weight_to_text'));
                                		?>
                                		<span class="txt_select"><span class="weight_to_text">kg</span></span> <span class="btn_combo_down"></span> 
						            </div>
						          </div>
						        </li>
						        <li>
						          <label class="title"><?php echo Lang::t('search', 'Height'); ?>:</label>
						          <div class="mar_lef_20">
						            <input type="text" placeholder="<?php echo Lang::t('search', 'From'); ?>" class="input-type-2 input_setting w70 left" id="height-from" name="height-from">
						            <div class="select_style w70">
						  				<?php
											echo CHtml::dropDownList('height-from-unit', false, array('0' => 'cm', '1' => 'm'), array('id' => 'height-from-unit', 'class' => 'select-type-3 virtual_form', 'text' => 'height_from_text'));
										?>
                                		<span class="txt_select"><span class="height_from_text">cm</span></span> <span class="btn_combo_down"></span>
						            </div>
						            <input type="text" placeholder="<?php echo Lang::t('search', 'To'); ?>" class="input-type-2 input_setting w70 left" id="height-to" name="height-to">
						            <div class="select_style w70">
										<?php
											echo CHtml::dropDownList('height-to-unit', false, array('0' => 'cm', '1' => 'm'), array('id' => 'txt-height-to', 'class' => 'select-type-3 virtual_form', 'text' => 'height_to_text'));
										?>
                                		<span class="txt_select"><span class="height_to_text">cm</span></span> <span class="btn_combo_down"></span> 
						            </div>
						          </div>
						        </li>
						        <li>
						          <label class="relationship title"><?php echo Lang::t('settings', 'Relationship Status'); ?>:</label>
						          <div class="mar_lef_20">
						            <div class="select_style w288">
										<?php
											$relationship = ProfileSettingsConst::getRelationshipLabel();
											echo CHtml::dropDownList('txt-relationship', false, $relationship, array('name' => 'txt-relationship', 'id' => 'txt-relationship', 'class' => 'select-type-5 virtual_form', 'text' => 'relationship_text', 'prompt' => ProfileSettingsConst::EMPTY_DATA));
										?>
                                		<span class="txt_select"><span class="relationship_text"><?php echo ProfileSettingsConst::EMPTY_DATA; ?></span></span> <span class="btn_combo_down"></span> 
						            </div>
						          </div>
						          <label class="title"><?php echo Lang::t('settings', 'Build'); ?>:</label>
						          <div class="mar_lef_20">
						            <div class="select_style w288">
										<?php
											$build = ProfileSettingsConst::getBuildLabel();
											echo CHtml::dropDownList('txt-body', false, $build, array('name' => 'txt-body', 'id' => 'txt-body', 'class' => 'select-type-5 virtual_form', 'text' => 'body_text', 'prompt' => ProfileSettingsConst::EMPTY_DATA));
										?>
                                			<span class="txt_select"><span class="body_text"><?php echo ProfileSettingsConst::EMPTY_DATA; ?></span></span> <span class="btn_combo_down"></span> 
						            </div>
						          </div>
						        </li>
						        <li>
						          <label class="title"><?php echo Lang::t('settings', 'Safe sex'); ?>:</label>
						          <div class="mar_lef_20">
						            <div class="select_style w288">
										<?php
											$safe_sex = ProfileSettingsConst::getSafeSexLabel();
											echo CHtml::dropDownList('txt-safe-sex', false, $safe_sex, array('name' => 'txt-safe-sex', 'id' => 'txt-safe-sex', 'class' => 'select-type-5 virtual_form', 'text' => 'safe_sex_text', 'prompt' => ProfileSettingsConst::EMPTY_DATA));
										?>
                                		<span class="txt_select"><span class="safe_sex_text"><?php echo ProfileSettingsConst::EMPTY_DATA; ?></span></span> <span class="btn_combo_down"></span> 
						            </div>
						          </div>
						          <label class="title"><?php echo Lang::t('settings', 'Ethnicity'); ?>:</label>
						          <div class="mar_lef_20">
						            <div class="select_style w288">
										<?php
											$ethnicity = ProfileSettingsConst::getEthnicityLabel();
											echo CHtml::dropDownList('txt-ethnics', false, $ethnicity, array('name' => 'txt-ethnics', 'id' => 'txt-ethnics', 'class' => 'select-type-5 virtual_form', 'text' => 'ethnicity_text', 'prompt' => ProfileSettingsConst::EMPTY_DATA));
										?>
                               	 		<span class="txt_select"><span class="ethnicity_text"><?php echo ProfileSettingsConst::EMPTY_DATA; ?></span></span> <span class="btn_combo_down"></span> 
						            </div>
						          </div>
						        </li>
						        <li class="bor_bot"></li>
						      </ul>
						      <div class="squaredCheck with_profile_pic">
						        <input type="checkbox" value="1" name="with_profile_pic" id="with_profile_pic" class="input-type-3 with_profile" checked="checked">
						        <label for="with_profile_pic"></label>
						        <label for="with_profile_pic" class="mar_left_24"><?php echo Lang::t('search', 'With Profile Picture'); ?></label>
						      </div>
						      <button class="btn btn-white"><?php echo Lang::t('search', 'Find'); ?></button>
						    </div>
						    </form>
						  </div>
						</div>                    
                    <!--  end popup search-->
                    <div class="block_loading showmore show-more-searchresult" style="display: none;"><a href="javascript:void(0);" onclick="showmore_searchresult();"><span></span></a></div>
					<input type="hidden" name="showmore_next_offset" id="showmore_next_offset" value="0" />
					<input type="hidden" name="showmore_type" id="showmore_type" value="quicksearch" />    
					<input type="hidden" name="quicksearch_keyword" id="quicksearch_keyword" value="<?php echo $q; ?>" />                    
                        