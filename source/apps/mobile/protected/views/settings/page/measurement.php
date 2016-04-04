				<?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmSaveSettings',
                    'action' => $this->user->createUrl('/settings/save'),
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
                        ));
                ?>                    
				<div class="pad_10">
				  <div class="setting_gen setting_management">
				    <p class="title left"><a title="Back" href="<?php echo $this->usercurrent->createUrl('//settings/index');?>" class="link_back"></a><span><?php echo Lang::t('settings', 'Measurement'); ?></span></p>
				    <ul>
				    
				      <?php if($model->measurement == 1): ?>
				      <li> 
				      	<span>
					        <input type="radio" value="2" name="measurement" id="measurement_feet_lps" class="measurement" />
					        lbs / ft
				        </span> 
				        <span>
					        <input type="radio" value="1" name="measurement" id="measurement_kg_cm" class="measurement" checked="checked" />
					        Kg / cm
				        </span> 
				      </li>
				      <?php endif; ?>
				      
				      <?php if($model->measurement == 2): ?>
				      <li> 
				      	<span>
					        <input type="radio" value="2" name="measurement" id="measurement_feet_lps" class="measurement" checked="checked" />
					        LPS / Feet
				        </span> 
				        <span>
					        <input type="radio" value="1" name="measurement" id="measurement_kg_cm" class="measurement"/>
					        Kg / cm
				        </span> 
				      </li>
				      <?php endif; ?>
				      
				      				      
				      <li class="but_func"><a class="but active" href="javascript:void(0);" onClick="saveMeasurement();"><?php echo Lang::t('settings', 'Save'); ?></a> 
				      		<a href="<?php echo $this->usercurrent->createUrl('settings/index'); ?>" class="but"><?php echo Lang::t('settings', 'Discard'); ?></a></li>
				    </ul>
				  </div>
				</div>
                    <?php $this->endWidget(); ?>