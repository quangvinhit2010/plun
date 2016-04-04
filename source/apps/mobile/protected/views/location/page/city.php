<?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmSaveSettings',
                    'action' => '/location/CitySave',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'
                        )));
                ?>
                		name
                        <?php echo $form->textField($model, 'name', array('placeholder' => Lang::t('settings', 'Enter text'))); ?>
                         code                      
                                                <?php echo $form->textField($model, 'code', array('placeholder' => Lang::t('settings', 'Enter text'))); ?>
                        
                <select id="SysCity_country_id" name="SysCity[country_id]" onchange="getCity('sl-country', 'sl-city', 'sl-district');" class="sl-country">

<option value="230">Viet Nam</option>

</select>
    
                                <input type="submit" value="send">
                                 <?php $this->endWidget(); ?>