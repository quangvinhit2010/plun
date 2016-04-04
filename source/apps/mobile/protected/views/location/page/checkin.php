<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/location/common.js');

				$country =	$country_in_cache->getCountryInfo($profile_location->current_country_id);
				if($profile_location->current_city_id){
					$city =	$city_in_cache->getCityInfo($profile_location->current_city_id);
				}
				if($profile_location->current_state_id){
					$state =	$state_in_cache->getStateInfo($profile_location->current_state_id);
				}				
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmSaveSettings',
                    'action' => '/location/save',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'
                        )));
                ?>
                
 				<div class="checkin">
                    	<p class="title"><label></label> <?php echo Lang::t('settings', 'Check in'); ?></p>
                        <ul class="left">
                          <li>                             
	                            <div class="select_style check-in-country">
	                            	<?php 
									    $list_country = CHtml::listData($list_country, 'id', 'name');
									    $top_country	=	SysCountry::model()->getCountryTop();
									    $top_country	= CHtml::listData($top_country, 'id', 'name');
									    $list_country_group	=	array(' ' => $top_country,'------------' => $list_country);
									    echo CHtml::dropDownList('country_id',$profile_location->current_country_id, $list_country_group, array('text' => 'country_checkin_text','name' => 'txt-country', 'id' => 'txt-country', 'onchange' => "getStateCheckIn();",'class' => 'select-type-1 ci-country virtual_form'));                
	                                ?>
	                                <span class="txt_select"><span class="country_checkin_text"><?php echo $country['name']; ?></span></span> <span class="btn_combo_down"></span>
	                            </div> 
	                      </li>    
	                      <?php if(sizeof($list_state)){ ?>
	                      <li class="check-in-state">
	                            <div class="select_style">
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
										echo CHtml::dropDownList('state_id',$profile_location->current_state_id, $list_state_group, array('text' => 'state_checkin_text','name' => 'txt-state', 'id' => 'txt-state', 'onchange' => "getCityCheckIn();",'class' => 'select-type-1 ci-state virtual_form', 'empty' => Lang::t('search', '--Any--'))); 
									?>  
	                                <span class="txt_select"><span class="state_checkin_text"><?php echo (isset($state['name'])) ? $state['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>
	                            </div>
	                       </li>
	                       <?php }else{ ?>
	                       		<li class="check-in-state" style="display: none;"><div class="select_style"></div></li>
	                       <?php } ?>         
	                                              
                            <?php 
                               //list city
                               if(sizeof($list_city)){
								$list_city = CHtml::listData($list_city, 'id', 'name');
							?>
							<li class="check-in-city">
	                            <div class="select_style">
	                            	<?php
	                            		echo CHtml::dropDownList('city_id',$profile_location->current_city_id, $list_city, array('text' => 'city_checkin_text','name' => 'txt-city', 'id' => 'txt-city', 'onchange' => "getDistrictCheckIn();",'class' => 'select-type-1 ci-city virtual_form', 'empty' => Lang::t('search', '--Any--'))); 
	                            	?>
	                                <span class="txt_select"><span class="city_checkin_text"><?php echo (isset($city['name'])) ? $city['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>
	                            </div>
	                        </li>
                            <?php }else{ ?> 
                            	<li class="check-in-city" style="display: none;"><div class="select_style"></div></li>
                            <?php } ?>

                            <?php 
                            	//list district
                            	if(sizeof($list_district)){ 
								    $list_district = CHtml::listData($list_district, 'id', 'name');
                            ?>
                            <li class="check-in-district">
	                            <div class="select_style">
	                            	<?php 
	                            		echo CHtml::dropDownList('district_id',$district_id, $list_district, array('text' => 'district_checkin_text', 'name' => 'txt-district', 'id' => 'txt-district', 'class' => 'select-type-1 ci-district virtual_form', 'empty' => Lang::t('search', '--Any--'))); 
	                            	?>
	                                <span class="txt_select"><span class="district_checkin_text"><?php echo (isset($district['name'])) ? $district['name'] : ''; ?></span></span> <span class="btn_combo_down"></span>
	                            </div> 
	                        </li>    
                            <?php }else{?>      
                            	<li class="check-in-district" style="display: none;"><div class="select_style"></div></li>
                            <?php } ?>                             
                            
                        </ul>
                        <div class="save_cancel_checkin right">
                          	<a href="javascript:void(0);" onclick="return save_my_checkin();"><button class="btn btn-violet"><?php echo Lang::t('general', 'Save')?></button></a>
                            <a href="javascript:void(0);" onclick="window.location.href = '/'; return false;"><button class="btn btn-grey"><?php echo Lang::t('general', 'Cancel')?></button></a>
                        </div>
                    </div>
                                 <?php $this->endWidget(); ?>