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
                    <td class="title"><?php echo Lang::t('settings', 'Units'); ?>:</td>
                    <td>
                    	<?php if($model->measurement == 1): ?>
                    	<div class="left check_unit">
                    		<!-- 
                            <div class="roundedOne left">
                                <input type="checkbox" name="measurement" id="measurement_feet_lps" value="2" class="measurement" />
                                <label for="measurement_feet_lps"></label>                            
                            </div>
                             -->
                             <input name="measurement" id="measurement_feet_lps" class="measurement" type="radio" value="2" />
                             <span class="left unit">lbs / ft</span>
                        </div>
                        <div class="left check_unit">
                        	<!-- 
                            <div class="roundedOne left">
                                <input type="checkbox" name="measurement" id="measurement_kg_cm" value="1" checked="checked" class="measurement" />
                                <label for="measurement_kg_cm"></label>                            
                            </div>
                             -->
                             <input name="measurement" id="measurement_kg_cm" class="measurement" type="radio" value="1" checked="checked" />
                             <span class="left unit">kg / cm</span>
                            
                            
                        </div>
                        <?php endif; ?>
                        
                        <?php if($model->measurement == 2): ?>
                      	<div class="left check_unit">
                      		<!-- 
                            <div class="roundedOne left">
                                <input type="checkbox" name="measurement" id="measurement_feet_lps" value="2" class="measurement" checked="checked" />
                                <label for="measurement_feet_lps"></label>                            
                            </div>
                             -->
                            <input name="measurement" id="measurement_feet_lps" class="measurement" type="radio" value="2" checked="checked" />
                            <span class="left unit">lbs / ft</span>
                        </div>
                        <div class="left check_unit">
                        	<!-- 
                            <div class="roundedOne left">
                                <input type="checkbox" name="measurement" id="measurement_kg_cm" value="1" class="measurement" />
                                <label for="measurement_kg_cm"></label>                            
                            </div>
                            -->
                            <input name="measurement" id="measurement_kg_cm" class="measurement" type="radio" value="1" />
                            <span class="left unit">kg / cm</span>
                        </div>
                        <?php endif; ?>                      
                        
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