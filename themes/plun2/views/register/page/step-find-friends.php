            	<?php
					$usercurrent = Yii::app()->user->data();	
					Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/register.js');
					Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/invite.js');
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
                                        <span class="line_process active"></span>
                                    </li>
                                    <li>
                                        <div class="active">
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
                        	<h4><?php echo Lang::t('register', 'Are your friends already On Plun?'); ?></h4>
                            <p><?php echo Lang::t('register', 'Many of your friends may already be here. Searching your email account is the fastest way to find your friends on Plun.'); ?></p>
                        </div>
                        <div class="box_detail">
                        	<div class="find_friends">
                            	<ul class="list_social clearfix">
                                	<li style="display: none;">
                                    	<a class="find-friend right" action="/invitation/frontend/GetFriendsYahoo" openid="yahoo" href="javascript:void(0);" href="javascript:void(0);"><?php echo Lang::t('register', 'Find Friends'); ?></a>
                                        <a class="find-friend" action="/invitation/frontend/GetFriendsYahoo" openid="yahoo" href="javascript:void(0);"><i class="icon_common icon_find_ya"></i>Yahoo</a>
                                    </li>
                                    <li>
                                    	<a class="find-friend right" action="/invitation/frontend/GetFriendsGoogle" openid="google" href="javascript:void(0);"><?php echo Lang::t('register', 'Find Friends'); ?></a>
                                        <a class="find-friend" action="/invitation/frontend/GetFriendsGoogle" openid="google" href="javascript:void(0);">
                                        	<i class="icon_common icon_find_gmail"></i>Gmail
                                        </a>
                                    </li>
                                </ul>
                                <div class="left plun_friends" style="display: none;">
                                	
                                </div>
                            </div>
                        </div>
                        <div class="btn_footer">
                        	<a href="<?php echo Yii::app()->createUrl('/register/stepUpdateProfile');?>" class="btn_big bg_gray left"><?php echo Lang::t('general', 'Back'); ?></a>
                        	<a href="<?php echo Yii::app()->createUrl('/register/StepSuggest');?>" class="btn_big bg_gray right"><?php echo Lang::t('settings', 'Next Step'); ?></a>
                        </div>
                    </div>
                </div>
                <!-- InstanceEndEditable -->