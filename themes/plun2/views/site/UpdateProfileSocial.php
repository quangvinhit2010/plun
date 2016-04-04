            <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/settings.js'); ?>
            
            <!-- InstanceBeginEditable name="doctitle" -->
			<div class="sign_in_up">
            	<div class="outer-wrapper">
                    <div class="sign_form signup_social">
                        <div class="inner_wrap">
                            <div class="title"><?php echo Lang::t('register', 'Sign Up');?></div>
                                  
                                  <?php $form=$this->beginWidget('CActiveForm', array(
        		                    'id'=>'update-social-form',
        		                    'enableClientValidation'=>true,
        		                    'clientOptions'=>array(
        		                        'validateOnSubmit'=>true
        		                    ),
                                  	'htmlOptions' => array("onsubmit"=>"return UpdateProfileSocial.UpdateProfile();"),
        		                )); ?>
                            <div class="content">
                        	<ul>
                            	<li class="mar_right_20">
                                	<div class="UpdateProfileSocialUsername error">
                                        <input name="UpdateSocial[username]" value="<?php echo $username; ?>" id="updateSocial_username" type="text" placeholder="Thanhvo">
                                    	<div class="error_block">
                                            <label class="arrow"></label>
                                            <div class="errorMessage">
                                                <?php echo Lang::t('register', 'You can use different username'); ?>
                                            </div>                    		        
                                        </div>
                                    </div>
                                </li>
                                <li>
                                	<div>
                                        <div class="select_style w94 mar_right_10">
                                            <?php
						                        $birthday_month	=	BirthdayHelper::model()->getMonth();
					                        	echo Chtml::dropDownList('UpdateSocial[birthday_month]', 1, $birthday_month, array('id' => 'UpdateSocial_birthday_month','class' => 'sl-month virtual_form', 'text' => 'birthday_month_text'));
					                        ?>                                  
                                            <span class="txt_select"><span class="birthday_month_text"><?php echo Lang::t('time', 'JANUARY'); ?></span></span> 
                                            <span class="btn_combo_down"></span>
                                        </div>
                                        <div class="select_style w61 mar_right_10">
				                            <?php
				                            	echo Chtml::dropDownList('UpdateSocial[birthday_day]', false, BirthdayHelper::model()->getDates(), array('id' => 'UpdateSocial_birthday_day','class' => 'sl-day virtual_form', 'text' => 'birthday_day_text'));
				                            ?>
                            
                                            <span class="txt_select"><span class="birthday_day_text">1</span></span> 
                                            <span class="btn_combo_down"></span>
                                        </div>
                                        <div class="select_style w61">
                                            <?php
				                            	echo Chtml::dropDownList('UpdateSocial[birthday_year]', date('Y') - 17, BirthdayHelper::model()->getYears(), array('id' => 'UpdateSocial_birthday_year','class' => 'sl-year virtual_form', 'text' => 'birthday_year_text'));
				                            ?>
                                                   
                                            <span class="txt_select"><span><?php echo date('Y') - 17; ?></span></span> 
                                            <span class="btn_combo_down"></span>
                                        </div>
                                        <div class="error_block">
                                            <label class="arrow"></label>
                                            <div class="errorMessage">
                                                Date of birth cannot be blank.
                                            </div>                    		        
                                         </div>
                                     </div>
                                </li>
                                <li class="mar_right_20">
                                	<div>
                                        <div class="select_style w100P">
                                         	
                                         	<?php
				                            	$sexuality	=	ProfileSettingsConst::getSexualityLabel();
				                           		echo Chtml::dropDownList('UpdateSocial[sexuality]', 1, $sexuality, array('class' => 'virtual_form', 'text' => 'sexuality_text'));                                
				                            ?>
	                                                               
                                            <span class="txt_select sexuality_text"><span>Gay</span></span> 
                                            <span class="btn_combo_down"></span>
                                        </div>
                                     </div>
                                </li>
                                <li class="mar_right_20">
                                	<div>
                                        <div class="select_style w100P">         
			                                <?php
				                                $role	=	ProfileSettingsConst::getSexRoleLabel();
				                           		echo Chtml::dropDownList('UpdateSocial[sex_role]', 1 , $role, array('id' => 'UpdateSocial_sex_role', 'class' => 'virtual_form', 'text' => 'sex_role_text'));                                
				                            ?>
	                                                   
                                            <span class="txt_select sex_role_text"><span>Top</span></span> 
                                            <span class="btn_combo_down"></span>
                                        </div>
                                     </div>
                                </li>
                                <li>
                                	<div>
                                        <div class="select_style w100P">           
                                            <?php
                                            	$country_in_cache = new CountryonCache();
                                            	$list_country = $country_in_cache->getListCountry();
											
												$list_country = CHtml::listData($list_country, 'id', 'name');
												
												$top_country	=	array();
												
												$top_country1 = SysCountry::model()->getCountryTop();
												
												foreach($top_country1 AS $row){
													array_push($top_country, $row);
												}
												
												$top_country = CHtml::listData($top_country, 'id', 'name');
												$list_country_group = array('----------- ' => $top_country, '-----------' => $list_country);								
												
											
			                    	       		$list_country = CHtml::listData($list_country, 'id', 'name');
			                    	            echo CHtml::dropDownList('UpdateSocial[country_id]', 230, $list_country_group, array('name' => 'txt-country','id' => 'UpdateSocial_country_id','class' => 're-country virtual_form', 'text' => 'country_register_text'));
			                    	        ?>
		                    	                              
                                            <span class="txt_select country_register_text"><span>Vietnam</span></span> 
                                            <span class="btn_combo_down"></span>
                                        </div>
                                     </div>
                                </li>
                            </ul>
                        	
                            <div class="but_sign">
                            	<input type="submit" value="<?php echo Lang::t('register', 'Sign up now'); ?>" class="btn_submit">
                            </div>
                            
                        </div>
                        <?php $this->endWidget(); ?>
                            <div class="gaussian_blur blur"></div>
                        </div>
                    </div>
                </div>
            </div>