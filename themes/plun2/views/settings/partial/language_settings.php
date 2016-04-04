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
                                            <li><a href="<?php echo $userCurrent->createUrl('//settings/extra');?>"><?php echo Lang::t('settings', 'Extra'); ?></a></li>
                                        </ul>    
                                    </div>
                                	<div class="">
                                    	<h3><?php echo Lang::t('settings', 'Account Settings'); ?></h3>
                                    	<ul>
                                            <li><a class="active" href="<?php echo $userCurrent->createUrl('//settings/languages');?>"><?php echo Lang::t('settings', 'Languages & Change Units'); ?></a></li>
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
					              <h3><?php echo Lang::t('settings', 'Languages & Change Units'); ?></h3>
					              <div class="">
					                <table width="400" cellspacing="0" cellpadding="0" border="0">
									 <tbody>    
					                  <tr>
					                    <td class="title"><?php echo Lang::t('settings', 'Language Preference'); ?>:</td>
					                    <td>
					                    	<div class="select_style w160">
					                            <?php 
					                            	$languages	=	ProfileSettingsConst::getLanguagesDefault();
					                            	$languages_selected	=	isset($languages[$model->default_language])	?	$languages[$model->default_language]	:	$languages[ProfileSettingsConst::LANGUAGES_DEFAULT_VIETNAMESE];
					                            	echo $form->dropDownList($model, 'default_language', $languages, array('class' => 'virtual_form', 'text' => 'language_preference')); 
					                            ?>
					                            <span class="txt_select"><span class="language_preference"><?php echo $languages_selected; ?></span></span> <span class="btn_combo_down"></span>
					                        </div>
					                    </td>
					                  </tr>
					                  <tr>
					                    <td class="title"><?php echo Lang::t('settings', 'Units'); ?>:</td>
					                    <td>
					                    	<?php if($model->measurement == 1): ?>
					                    	<div class="left check_unit">
					                             <input name="measurement" id="measurement_feet_lps" class="measurement" type="radio" value="2" />
					                             <span class="left unit">lbs / ft</span>
					                        </div>
					                        <div class="left check_unit">
					                             <input name="measurement" id="measurement_kg_cm" class="measurement" type="radio" value="1" checked="checked" />
					                             <span class="left unit">kg / cm</span>
					                            
					                            
					                        </div>
					                        <?php endif; ?>
					                        
					                        <?php if($model->measurement == 2): ?>
					                      	<div class="left check_unit">
					                            <input name="measurement" id="measurement_feet_lps" class="measurement" type="radio" value="2" checked="checked" />
					                            <span class="left unit">lbs / ft</span>
					                        </div>
					                        <div class="left check_unit">
					                            <input name="measurement" id="measurement_kg_cm" class="measurement" type="radio" value="1" />
					                            <span class="left unit">kg / cm</span>
					                        </div>
					                        <?php endif; ?>                      
					                        
					                    </td>
					                  </tr> 
					                  <tr>
						                    <td></td>
						                    <td>
						                        <a class="right save_change" href="javascript:void(0);" onClick="Settings.saveLanguageSettings();"><?php echo Lang::t('general', 'Save Changes'); ?></a>
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