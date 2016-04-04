<?php if($list_state):
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
											echo CHtml::dropDownList('UsrProfileSettings[state_id]',0, $list_state_group, array('name' => 'txt-state', 'id' => 'UsrProfileSettings_state_id', 'onchange' => "getCityRegister();",'class' => 're-state virtual_form', 'text' => 'state_register_text','empty' => Lang::t('search', '--Any--'))); 
										?>                                    							
                                        <span class="txt_select user_looking_textselect">
                                            <span class="select_city state_register_text"><?php echo Lang::t('search', '--Any--'); ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                        <?php endif; ?>