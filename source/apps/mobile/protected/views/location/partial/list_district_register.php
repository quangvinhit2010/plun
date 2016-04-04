<?php 
											if(sizeof($list_district)):
												$list_district = CHtml::listData($list_district, 'id', 'name');
												echo CHtml::dropDownList('UsrProfileSettings[district_id]',0, $list_district, array('name' => 'txt-district', 'id' => 'UsrProfileSettings_district_id', 'class' => 're-district virtual_form', 'text' => 'district_register_text', 'empty' => Lang::t('search', '--Any--'))); 
										?>						
                                       <span class="txt_select user_looking_textselect">
                                            <span class="select_city district_register_text"><?php echo Lang::t('search', '--Any--'); ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                        <?php endif; ?>