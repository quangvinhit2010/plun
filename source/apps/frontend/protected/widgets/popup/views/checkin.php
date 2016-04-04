<?php 

$current_location	=	array();
if($district_id){
	$district =	$district_in_cache->getDistrictInfo($district_id);
	$current_location[]	=	$district['name'];
}
if($city_id){
	$city =	$city_in_cache->getCityInfo($city_id);
	$current_location[]	=	$city['name'];
}
if($state_id){
	$state =	$state_in_cache->getStateInfo($state_id);
	$current_location[]	=	$state['name'];
}
if($country_id){
	$country =	$country_in_cache->getCountryInfo($country_id);
	$current_location[]	=	$country['name'];
}
?>				        
				        
				        <div id="popup_checkIn" class="check_in popupSuggestSearch" style="display:none;">
                        	<div class="wrap_check_in wrapPopupSuggestSearch">
                                <div class="frm_checkIn">
                                    <div class="title_custom">
                                        <a href="javascript:void(0);" class="right" onclick="Location.resetCheckinform();"><span class="icon_common"></span><?php echo Lang::t('general', 'Reset')?></a>
                                        <p><b><?php echo Lang::t('settings', 'Check in'); ?></b></p>
                                    </div>
                                    <div class="items_frm">
		                            <div class="select_style">
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
										<select text="country_checkin_text" class="virtual_form ci-country" id="txt-country" name="txt-country" onChange="Location.getStateListCheckin();">
											<?php echo $html_listcountry; ?>
										</select>
		                                
		                                <span class="txt_select">
		                                	<span class="country_checkin_text"><?php echo $country['name']; ?></span>
		                                </span> 
		                                <span class="btn_combo_down"></span>
		                            </div>
		                            <?php if(sizeof($list_state)){ ?>
		                            <div class="select_style check-in-state">
										<?php
											$list_state = CHtml::listData($list_state, 'id', 'name');
			
		                            		if($country_id){
												$top_state	=	LocationState::model()->getTopStateByCountry($country_id);
											    $top_state	=	CHtml::listData($top_state, 'id', 'name');
											    if(sizeof($top_state)){
													$list_state_group	=	array(' ' => $top_state, '------------' => $list_state);
												}else{
													$list_state_group	=	$list_state;
												}
											}else{
												$list_state_group	=	$list_state;
											}																
											echo CHtml::dropDownList('state_id',$state_id, $list_state_group, array('text' => 'text-check-in-state','name' => 'txt-state', 'id' => 'txt-state', 'onchange' => "Location.getCityListCheckin();",'class' => 'select-type-1 ci-state virtual_form', 'empty' => Lang::t('search', '--Any--'))); 
										?>  
		                                <span class="txt_select"><span class="text-check-in-state"><?php echo (isset($state['name'])) ? $state['name'] : Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
		                            </div>
		                            <?php }else{ ?>
		                            	<div class="select_style check-in-state" style="display: none;">
			                            	<select text="text-check-in-state" class="select-type-1 ci-state virtual_form" onchange="Location.getCityListCheckin();" id="txt-state" name="state_id" text="text-check-in-state">
			                            	</select>
			                            	<span class="txt_select"><span class="text-check-in-state"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
			                            	
		                            	</div>
		                            <?php } ?>
		                            
		                            <?php 
		                               //list city
		                               if(sizeof($list_city)){
										$list_city = CHtml::listData($list_city, 'id', 'name');
									?>
		                            <div class="select_style check-in-city">
		                            	<?php
		                            		echo CHtml::dropDownList('city_id',$city_id, $list_city, array('text' => 'text-check-in-city','name' => 'txt-city', 'id' => 'txt-city', 'onchange' => "Location.getDistrictListCheckin();",'class' => 'select-type-1 ci-city virtual_form', 'empty' => Lang::t('search', '--Any--'))); 
		                            	?>
		                                <span class="txt_select"><span class="text-check-in-city"><?php echo (isset($city['name'])) ? $city['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>
		                            </div>
		                            <?php }else{ ?> 
		                            	<div class="select_style check-in-city" style="display: none;">
                            				<select text="text-check-in-city" name="city_id" id="txt-city" onchange="Location.getDistrictListCheckin();" class="select-type-1 ci-city virtual_form">
                            				</select>
                            				<span class="txt_select"><span class="text-check-in-city"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
                            				
		                            	</div>
		                            <?php } ?>                            
		                                                        
		                                                        
		                            <?php 
			                            	//list district
			                            	if(sizeof($list_district)){ 
											    $list_district = CHtml::listData($list_district, 'id', 'name');
			                            ?>
			                            <div class="select_style check-in-district">
			                            	<?php 
			                            		echo CHtml::dropDownList('district_id',$district_id, $list_district, array('text' => 'text-check-in-district', 'name' => 'txt-district', 'id' => 'txt-district', 'class' => 'select-type-1 ci-district virtual_form', 'empty' => Lang::t('search', '--Any--'))); 
			                            	?>
			                                <span class="txt_select"><span class="text-check-in-district"><?php echo (isset($district['name'])) ? $district['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>
			                            </div> 
			                            <?php }else{?>      
			                            	<div class="select_style check-in-district" style="display: none;">
			                            		<select text="text-check-in-district" name="txt-district" id="txt-district" class="select-type-1 ci-district virtual_form"></select>
			                            		<span class="txt_select"><span class="text-check-in-district"><?php echo Lang::t('search', '--Any--'); ?></span></span> <span class="btn_combo_down"></span>
			                            	</div>
			                            <?php } ?> 
									</div>
                                </div>
                                <div class="footer_checkIn suggestLoactionCommon">
                                    <div class="left">
                                        <div>
                                            <span class="icon_common icon_loca"></span>
                                            <input class="txtSearchLoaction" type="text" placeholder="add location" id="check_in_checklocation"  name="check_in_checklocation" />
                                            <span class="icon_common icon_dle"></span>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="btn_small btn_tim right" onclick="Location.saveCheckin();"><?php echo Lang::t('general', 'Save')?></a>
                                    <div class="list_suggers_location" style="display:none;">
                                        <ul class="clearfix">

                                        </ul>
                                    </div>
                                    <input type="hidden" name="suggest_text_venue" id="suggest_text_venue" value="" />
                                    <input class="inputHiddenVenue" type="hidden" name="suggest_id_venue" id="suggest_id_venue" value="0" />
                                </div>


                            </div>
                        </div>
                        
                        <?php 
                        Yii::app()->clientScript->registerScript('checkin_show_whocheckin',"
							$(document).on('click','.list_suggers_location ul li p a',function(){
								objCommon.loading();
								$.ajax({
									type: 'POST',
									url: $(this).attr('data-url'),
									dataType: 'html',
									success: function(res){
										$('#popup_listUserLoca').html(res);
										l($('.u_list_add .scrollPopup ul li').length);
										if($('#popup_listUserLoca .scrollPopup ul li').length > 5){
										    objCommon.sprScroll('#popup_listUserLoca .scrollPopup');
										}else{
										    $('#popup_listUserLoca .scrollPopup').css('height','auto');
										}
										objCommon.unloading();
										$('#popup_listUserLoca').pdialog({
											title: '" . Lang::t('venue', 'People were here'). "',
											open: function(){
												objCommon.outSiteDialogCommon(this);
												objCommon.have_title();
											},
											width: 445
										});
									}
								});
								return false;	
							});
							     ",
                        CClientScript::POS_END);
                        ?>
                        
                        <div id="popup_listUserLoca" class="u_list_add popup_genneral" style="display:none;"></div>