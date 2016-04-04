               <?php 
					Yii::app()->clientScript->registerScript('settings',"
						$(document).ready(function(){
							objCommon.sprScroll('.wrap_suggest');
						});
						",
					CClientScript::POS_END);
					$userCurrent =  Yii::app()->user->data();
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
                                         <span class="line_process active"></span>
                                    </li>
                                    <li>
                                    	<div class="active">
                                            <span class="icon_common icon_round"></span>
                                            <span><?php echo Lang::t('register', 'Suggest Friends'); ?></span>
                                        </div>
                                        
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="header_page">
                        	<h4><?php echo Lang::t('register', 'Suggest Friends'); ?></h4>
                            <p><?php echo Lang::t('register', 'Add more people, get more fun.'); ?></p>
                        </div>
                        <div class="box_detail">
                        	<div class="suggest_friends">
                            	<div class="wrap_suggest clearfix">
                                	<ul class="list_suggest">
                                		<?php 
                                		$params = CParams::load ();
                                		$img_webroot_url	=	$params->params->img_webroot_url;
                                		foreach ($data as $key => $item): 
										    $item	=	$item['_source'];
										    $url = Yii::app()->createUrl('//my/view', array('alias' => $item['alias_name']));
										    if($item['have_avatar']){
										    	$avatar	=	"http://{$img_webroot_url}{$item['avatar']}";
										    }else{
										    	$avatar	=	$item['avatar'];
										    }
				                        ?>
                                    	<li>
                                        	<a href="<?php echo $url; ?>" class="wrap_img" target="_blank">
                                        		<img src="<?php echo $avatar; ?>" width="119px" height="119px" onerror="$(this).attr('src','/public/images/no-user.jpg');"/>
                                        	</a>
                                            <a href="<?php echo $url; ?>" target="_blank"><?php echo $item['username']; ?></a>
                                            <span><?php echo $item['total_friends']; ?> <?php echo Lang::t('register', 'Friends');?></span>
                                        </li>
                                       <?php 
                                       		endforeach;
                                       ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="btn_footer">
                        	<a href="<?php echo Yii::app()->createUrl('/register/StepFindFriends');?>" class="btn_big bg_gray left"><?php echo Lang::t('settings', 'Back'); ?></a>
                        	<a href="/" class="btn_big bg_tim right"><?php echo Lang::t('register', 'Finish'); ?></a>
                        </div>
                    </div>
                </div>
                <!-- InstanceEndEditable -->