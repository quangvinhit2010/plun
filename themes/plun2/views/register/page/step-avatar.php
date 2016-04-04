            	<?php
					$usercurrent = Yii::app()->user->data();	
					Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/register.js');
					Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/js/jquery.Jcrop.js', CClientScript::POS_BEGIN);
					Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/css/jquery.Jcrop.css');
					$urlAvatar =	Yii::app()->theme->baseUrl . '/resources/html/css/images/avatar_upload.png';
				?>
            	<!-- InstanceBeginEditable name="doctitle" -->
                <div class="container pheader min_max_926 page_step"> 
					<div class="explore left clearfix">
                    	<div class="title_top">
                        	<div class="process_bar clearfix">
                            	<ul>
                                	<li>
                                    	<div class="active">
	                                        <span class="icon_common icon_round"></span>
                                            <span><?php echo Lang::t('settings', 'Update Profile'); ?></span>
                                            
                                        </div>
                                        <span class="line_process active"></span>
                                    </li>
                                    <li>
                                        <div class="active">
                                            <span class="icon_common icon_round"></span>
                                            <span><?php echo Lang::t('register', 'Set Avatar'); ?></span>
                                            
                                        </div>
                                        <span class="line_process"></span>
                                    </li>
                                    <li>
                                        <div>
                                            <span class="icon_common icon_round"></span>
                                            <span><?php echo Lang::t('register', 'Find Friends'); ?></span>
                                            
                                        </div>
                                         <span class="line_process"></span>
                                    </li>
                                    <li>
                                    	<div>
                                            <span class="icon_common icon_round"></span>
                                            <span><?php echo Lang::t('register', 'Suggest Friends'); ?></span>
                                        </div>
                                        
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="header_page">
							<h4><?php echo Lang::t('register', 'Fill out your profile info'); ?></h4>
							<p><?php echo Lang::t('register', 'This information will help you find your friends on Plun.'); ?></p>
                        </div>
                        <div class="box_detail">
                                <?php
					            	$form = $this->beginWidget('CActiveForm', array(
					                'id' => 'frmCropAvatar',
					                'action' => $usercurrent->createUrl('//my/UploadAvatar'),
					                'enableClientValidation' => true,
					                'enableAjaxValidation' => true,
					                'htmlOptions' => array(
					                    'enctype' => 'multipart/form-data', 
					                    'class'=>'frmFillOut'
					                    )
					                ));
					            ?>
                        	<div class="upload_avatar">
				                <div class="frame">
				                    <div class="wrap-upload-image">
				                        <a href="javascript:void(0);" onclick="$('#uploadImage').trigger( 'click' ); return false;">
				                        	<img id="uploadPreview" src="<?php echo $urlAvatar;?>?time=<?php echo time();?>"/>
				                        </a>
				                    </div>
				                </div>
                                <a href="javascript:void(0);" onclick="$('#uploadImage').trigger( 'click' ); return false;"><?php echo Lang::t('register', 'Upload photo');?></a>
                            </div>
                            <div class="upload_avatar" style="display: none;">
			        	        <input type="file" id="uploadImage">
			        	    </div>
                            <input type="hidden" id="x" name="x" />
			    			<input type="hidden" id="y" name="y" />
			    			<input type="hidden" id="w" name="w" />
			    			<input type="hidden" id="h" name="h" />
			    			<input type="hidden" id="upload_url" name="upload_url" value="<?php echo Yii::app()->createUrl('//my/UploadAvatar', array('f'=>$usercurrent->alias_name))?>" />
                            <?php $this->endWidget(); ?>
                        </div>
                        <div class="btn_footer">
                        	<a href="<?php echo Yii::app()->createUrl('/register/stepUpdateProfile');?>" class="btn_big bg_gray left"><?php echo Lang::t('settings', 'Back'); ?></a>
                        	<a href="javascript:void(0);" class="btn_big bg_gray right" onclick="Register.scropAvatar();"><?php echo Lang::t('settings', 'Next Step'); ?></a>
                        </div>
                    </div>
                </div>
                <!-- InstanceEndEditable -->