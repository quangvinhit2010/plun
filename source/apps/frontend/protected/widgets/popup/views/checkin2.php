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
                                <span class="closePopupSuggest"></span>
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
		                           
                                    
                                    <div class="select_style search-by-venues">
		         						<input type="text" name="input_venues" id="input_venues" style="width: 226px;display: none;" />
		         						<?php if($venues_data){ ?>								 
											<a href="javascript:void(0);" onclick="Location.clearVenue(this);" class="remove_venues_value"></a>
											<input type="hidden" name="suggest_text_venue" id="suggest_text_venue" value="<?php echo $venues_data['title']; ?>" />
											<input type="hidden" name="suggest_id_venue" id="suggest_id_venue" value="<?php echo intval($venues_data['venue_id']); ?>" />								
										<?php }else{ ?>
											<a href="javascript:void(0);" onclick="Location.clearVenue(this);" class="remove_venues_value" style="display: none;"></a>
											<input type="hidden" name="suggest_text_venue" id="suggest_text_venue" value="" />
											<input type="hidden" name="suggest_id_venue" id="suggest_id_venue" value="0" />
										<?php }	 ?>	
											<?php 
						                        $this->widget('backend.extensions.select2.ESelect2',array(
						                          'selector'=>"#input_venues",
							                  		'options'=>array(
													'placeholder'	=>	$venues_data	?	$venues_data['title']	:	Lang::t('general', 'Search a venue'),
						                            'allowClear'=>true,
						                            'minimumInputLength' => 2,
						                            'ajax'=>array(
						                                'url'=> '/venues/Suggest',
						                                'dataType'=>'json',
						                                'data'=>'js:function(term,page) { return {q: term, page_limit: 3, page: page}; }',
						                                'results'=>'js:function(data,page) { return {results: data}; }',
						                            ),
													'formatInputTooShort'=>'js:function(input, min) {
									                  		var n = min - input.length;
															return tr("Please enter &1 more characters", 2);
									                }',
													'formatResult'=>'js:function(item) {
															$(".select2-container .select2-choice abbr").hide();
															$("#suggest_text_venue").val("");
															$("#suggest_id_venue").val(0);
								                          	return item.text;
								                     }',
													'formatSelection' => 'js: function(item) {
																$("#suggest_text_venue").val(item.text);
																
																if($("#suggest_text_venue") != ""){
																	$(".check_in .select2-container .select2-choice abbr").remove();
																}			
																$(".venues_value").remove();
																$(".remove_venues_value").show();
																$(".select2-container .select2-choice span").html("");
																$("#suggest_id_venue").val(item.id);
													            return item.text;
													 }'
						                          )
						                        ));
											?>										
																			
									</div>
                                    </div>
                                </div>
                                <div class="footer_checkIn">
                            	<div class="right">
                                	<a href="javascript:void(0);" class="btn_small btn_gray" onclick="Location.closeCheckinform();"><?php echo Lang::t('general', 'Cancel')?></a>
                                    <a href="javascript:void(0);" class="btn_small btn_tim" onclick="Location.saveCheckin();"><?php echo Lang::t('general', 'Save')?></a>
                                </div>
                            </div>
                            </div>
                        </div>