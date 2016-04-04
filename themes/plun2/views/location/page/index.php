<?php

                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmSaveSettings',
                    'action' => '/location/save',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'
                        )));
                ?>
                
                        <?php echo $form->textField($model, 'name', array('placeholder' => Lang::t('settings', 'Enter text'))); ?>
                <select id="SysDistrict_country_id" name="SysDistrict[country_id]" onchange="getCity('sl-country', 'sl-city', 'sl-district');" class="sl-country">

<option value="230">Viet Nam</option>

</select>
                                                <?php
                                
                                $list_city = CHtml::listData($list_city, 'id', 'name');
                                echo $form->dropDownList($model, 'city_id', $list_city, array('class' => 'sl-city', 'onchange' => "getDistrictList('sl-city', 'sl-district');"));
                                ?>
                                <input type="submit" value="send">
                                 <?php $this->endWidget(); ?>