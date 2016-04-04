<div class="left setting_col1">                 
                       <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'frmSaveSettings',
                            'action' => $this->user->createUrl('/settings/languageSettings'),
                            'enableClientValidation' => true,
                            'enableAjaxValidation' => true,
                            'htmlOptions' => array('enctype' => 'multipart/form-data', 'onsubmit' => 'return saveLanguageSettings();'),
                                ));
                        ?>           
            	<table width="" border="0" cellspacing="0" cellpadding="0">   
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
                    <td class="title">&nbsp;</td>
                    <td align="right">                    	
                        <a class="btn btn-violet" href="javascript:void(0);" title="<?php echo Lang::t('settings', 'Save Settings'); ?>" onClick="saveLanguageSettings();"><?php echo Lang::t('settings', 'Save Settings'); ?></a>
                    </td>
                  </tr>
                </table>
                <?php $this->endWidget(); ?>
</div>
<div class="left setting_col2">
</div>