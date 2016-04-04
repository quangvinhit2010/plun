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
                                            <li><a href="<?php echo $userCurrent->createUrl('//settings/languages');?>"><?php echo Lang::t('settings', 'Languages & Change Units'); ?></a></li>
                                    	    <li><a class="active" href="<?php echo $userCurrent->createUrl('//settings/changepass');?>"><?php echo Lang::t('settings', 'Change Email & Password'); ?></a></li>
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
					              <h3><?php echo Lang::t('settings', 'Change Email & Password'); ?></h3>
					              <div class="">
					                <table width="420" cellspacing="0" cellpadding="0" border="0">
									 <tbody>    
                  				 			<tr>
							                    <td class="title"><?php echo Lang::t('settings', 'Your Email Address'); ?>:</td>
							                    <td>
							                        <?php echo $form->textField($model_profile, 'email', array('class' => 'input_setting w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
							                    </td>
							                  </tr> 
											 <tr>
							                    <td class="title"><?php echo Lang::t('settings', 'Your Current Password'); ?>:</td>
							                    <td>
							                        <?php echo CHtml::passwordField('password', null,  array('class' => 'input_setting w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
							                    </td>
							                  </tr>                  
											 <tr>
							                    <td class="title"><?php echo Lang::t('settings', 'Your New Password'); ?>:</td>
							                    <td>
							                        <?php echo CHtml::passwordField('new_password', null,  array('class' => 'input_setting w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
							                    </td>
							                  </tr> 
											 <tr>
							                    <td class="title"><?php echo Lang::t('settings', 'Re-type new Password'); ?>:</td>
							                    <td>
							                        <?php echo CHtml::passwordField('re_new_password', null,  array('class' => 'input_setting w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
							                    </td>
							                  </tr>  
											<tr>
						                    <td></td>
						                    <td>
						                        <a class="right save_change" href="javascript:void(0);" onClick="Settings.save_account_settings();"><?php echo Lang::t('general', 'Save Changes'); ?></a>
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