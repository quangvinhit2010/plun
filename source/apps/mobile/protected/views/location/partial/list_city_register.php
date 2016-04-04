<?php if(sizeof($list_city)):
												$list_city = CHtml::listData($list_city, 'id', 'name');
											    echo CHtml::dropDownList('UsrProfileSettings[city_id]',0, $list_city, array('name' => 'txt-city', 'id' => 'UsrProfileSettings_city_id', 'onchange' => "getDistrictRegister();",'class' => 're-city virtual_form', 'text' => 'city_register_text', 'empty' => Lang::t('search', '--Any--'))); 
											?>						
                                         <span class="txt_select user_looking_textselect">
                                            <span class="select_city city_register_text"><?php echo Lang::t('search', '--Any--'); ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                        <?php endif; ?>