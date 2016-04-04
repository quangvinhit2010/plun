				<?php 
					Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/settings.js');
					Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/js/jquery.Jcrop.js', CClientScript::POS_BEGIN);
					Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/css/jquery.Jcrop.css');
					Yii::app()->clientScript->registerScript('settings',"
						$(document).ready(function(){
							objCommon.hw_common();
							objCommon.list_event();
							objCommon.sprScroll(\".setting_page .content_setting .content\");
						});
						$(window).resize(function(){
							objCommon.hw_common();			
						});
						",
					CClientScript::POS_END);
					$userCurrent =  Yii::app()->user->data();
				?>
        
                <!-- InstanceBeginEditable name="doctitle" -->
                <div class="container pheader min_max_1024 clearfix hasBanner_300 settings_page">
                	<div class="explore left">
                    	<div class="list_explore setting_page">                        
                        	<div class="left menu_setting">
                            	<div class="title"><?php echo Lang::t('settings', 'Settings'); ?></div>
                                <div class="list_menu">
                                	<div class="change_avatar">
                                    	<p><a href="<?php echo $userCurrent->getUserUrl();?>"><img width="160px" width="160px" src="<?php echo $userCurrent->getAvatar(false) ?>" align="absmiddle" class="imgAvatar" /></a></p>
                                        <p><a class="nick" href="<?php echo $userCurrent->getUserUrl();?>"><?php echo $userCurrent->username;?></a></p>
                                        <p>
                                        	<a href="javascript:void(0);" class="click_upload_avatar">(<?php echo Lang::t('settings', 'Click here to change your avatar'); ?>)</a>
                                        	<input type="file" id="upload_avatar" style="display: none;">
                                        	<input type="hidden" name="url_upload_avatar" id="url_upload_avatar" value="<?php echo $userCurrent->createUrl('//my/UploadAvatar', array())?>" />
                                        </p>
                                        <?php 
											$content =  $this->renderPartial('partial/upload-avatar-popup', array(), true);
											$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'cropAvatar', 'content'=>$content));
										?>
                                    </div>
                                    <div class="">
                                    	<h3><?php echo Lang::t('settings', 'Profile Settings'); ?></h3>
                                    	<ul>
                                            <li><a href="<?php echo $userCurrent->createUrl('//settings/index');?>"><?php echo Lang::t('settings', 'Basic Info'); ?></a></li>
                                            <li><a class="active" href="<?php echo $userCurrent->createUrl('//settings/extra');?>"><?php echo Lang::t('settings', 'Extra'); ?></a></li>
                                        </ul>    
                                    </div>
                                	<div class="">
                                    	<h3><?php echo Lang::t('settings', 'Account Settings'); ?></h3>
                                    	<ul>
                                            <li><a href="<?php echo $userCurrent->createUrl('//settings/languages');?>"><?php echo Lang::t('settings', 'Languages & Change Units'); ?></a></li>
                                    	    <li><a href="<?php echo $userCurrent->createUrl('//settings/changepass');?>"><?php echo Lang::t('settings', 'Change Email & Password'); ?></a></li>
                                        </ul>    
                                    </div>
                                </div>
                            </div>
                            <div class="left content_setting">
                              <?php
				                $form = $this->beginWidget('CActiveForm', array(
				                    'id' => 'frmSaveSettings',
				                    'action' => $this->user->createUrl('/settings/save'),
				                    'enableClientValidation' => true,
				                    'enableAjaxValidation' => true,
				                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
				                        ));
				                ?>
                                <div class="content left">
					              <h3><?php echo Lang::t('settings', 'Extra'); ?></h3>
					              <div class="">
					                <table width="420" cellspacing="0" cellpadding="0" border="0">
									 <tbody>    
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Occupation'); ?>:</td>
                    <td>
                    	<div class="select_style w230">
                    		    <?php
	                            	$occupation	=	ProfileSettingsConst::getOccupationLabel();
                            		$occupation_selected	=	isset($occupation[$model->occupation])	?	$occupation[$model->occupation]	:	$occupation[ProfileSettingsConst::OCCUPATION_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'occupation', $occupation, array('class' => 'virtual_form', 'text' => 'occupation_text'));                                
	                            ?>
                            <span class="txt_select"><span class="occupation_text"><?php echo $occupation_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Religion'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    		    <?php
	                            	$religion	=	ProfileSettingsConst::getReligionLabel();
                            		$religion_selected	=	isset($religion[$model->religion])	?	$religion[$model->religion]	:	$religion[ProfileSettingsConst::RELIGION_ATHEIST];
	                           		echo $form->dropDownList($model, 'religion', $religion, array('class' => 'virtual_form', 'text' => 'religion_text'));                                
	                            ?>
                            <span class="txt_select"><span class="religion_text"><?php echo $religion_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>                  
                  <tr>
                    <td class="title" align="left" valign="top"><?php echo Lang::t('settings', 'Attributes'); ?>:</td>
                    <td>
					    <?php 
                    		$my_attributes	=	ProfileSettingsConst::getAttributesLabel(); 
                    		$my_attributes_selected	=	!empty($model->my_attributes)		?	explode(',', $model->my_attributes) 	:	array();
                    		foreach($my_attributes AS $key => $value): 
                        		$checkbox_attributes     =    array(
                        		        'value'     => $key,
                        		        'class'     => 'my_attributes',
                        		        'id'        =>    'my_attributes_' . $key
                        		);
                        		if(in_array($key, $my_attributes_selected)){
                        		    $checked    =    true;
                        		}else{
                        		    $checked    =    false;
                        		}
                    	?>
                    	<div class="squaredCheck">
                    	    <?php echo CHtml::CheckBox('my_attributes[]', $checked, $checkbox_attributes); ?>
                            <label for="my_attributes_<?php echo $key; ?>"></label>
                            <label for="my_attributes_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
                        </div>
                        <?php endforeach; ?>

                    </td>
                  </tr>
                  
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Mannerism'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$mannerism	=	ProfileSettingsConst::getMannerismLabel();
                            		$mannerism_selected	=	isset($mannerism[$model->mannerism])	?	$mannerism[$model->mannerism]	:	$mannerism[ProfileSettingsConst::MANNERISM_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'mannerism', $mannerism, array('class' => 'virtual_form', 'text' => 'mannerism_text'));                                
	                        ?>
                            <span class="txt_select"><span class="mannerism_text"><?php echo $mannerism_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>            
                                          
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Smoke'); ?></td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$smoke	=	ProfileSettingsConst::getSmokeLabel();
                            		$smoke_selected	=	isset($smoke[$model->smoke])	?	$smoke[$model->smoke]	:	$smoke[ProfileSettingsConst::SMOKE_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'smoke', $smoke, array('class' => 'virtual_form', 'text' => 'smoke_text'));                                
	                        ?>
                            <span class="txt_select"><span class="smoke_text"><?php echo $smoke_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Drink'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$drink	=	ProfileSettingsConst::getDrinkLabel();
                            		$drink_selected	=	isset($drink[$model->drink])	?	$drink[$model->drink]	:	$drink[ProfileSettingsConst::DRINK_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'drink', $drink, array('class' => 'virtual_form', 'text' => 'drink_text'));                                
	                        ?>
                            <span class="txt_select"><span class="drink_text"><?php echo $drink_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Safe sex'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$safe_sex	=	ProfileSettingsConst::getSafeSexLabel();
                            		$safe_sex_selected	=	isset($safe_sex[$model->safer_sex])	?	$safe_sex[$model->safer_sex]	:	$safe_sex[ProfileSettingsConst::SAFESEX_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'safer_sex', $safe_sex, array('class' => 'virtual_form', 'text' => 'safer_sex_text'));                                
	                        ?>
                            <span class="txt_select"><span class="safer_sex_text"><?php echo $safe_sex_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>                  
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Club'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$club	=	ProfileSettingsConst::getClubLabel();
                            		$club_selected	=	isset($club[$model->club])	?	$club[$model->club]	:	$club[ProfileSettingsConst::CLUB_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'club', $club, array('class' => 'virtual_form', 'text' => 'club_text'));                                
	                        ?>
                            <span class="txt_select"><span class="club_text"><?php echo $club_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title" width="105"><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$public_information	=	ProfileSettingsConst::getPublicInformationLabel();
                            		$public_information_selected	=	isset($public_information[$model->public_information])	?	$public_information[$model->public_information]	:	$public_information[ProfileSettingsConst::PUBLIC_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'public_information', $public_information, array('class' => 'virtual_form', 'text' => 'public_information_text'));                                
	                        ?>
                            <span class="txt_select"><span class="public_information_text"><?php echo $public_information_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'I Live With'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$live_with	=	ProfileSettingsConst::getLiveWithLabel();
                            		$live_with_selected	=	isset($live_with[$model->live_with])	?	$live_with[$model->live_with]	:	$live_with[ProfileSettingsConst::LIVE_WITH_NOBODY];
	                           		echo $form->dropDownList($model, 'live_with', $live_with, array('class' => 'virtual_form', 'text' => 'live_with_text'));                                
	                        ?>
                            <span class="txt_select"><span class="live_with_text"><?php echo $live_with_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  						                  <tr>
						                    <td></td>
						                    <td>
						                        <a class="right save_change" href="javascript:void(0);" onClick="Settings.save_settings_extra();"><?php echo Lang::t('general', 'Save Changes'); ?></a>
						                        <a class="right" href="javascript:void(0);" onclick="Settings.resetForm();">Reset</a>
						                    </td>
						                  </tr>
                                                               
					                </tbody>
					                </table>
					              </div>
                                </div>
                                <?php $this->endWidget(); ?>
                            </div>
                    	</div>
                    	<?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_300)); ?>
                    </div>
                </div>    
                <!-- InstanceEndEditable -->