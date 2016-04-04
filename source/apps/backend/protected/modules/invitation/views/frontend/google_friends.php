                    <div class="left has_join">
                    	<h3><?php echo Lang::t('invitation', 'We found your friends here.'); ?></h3>
                        <div class="list">
                        	<?php if($friends['total']){ ?>
                        	<ul>
                        		 <?php 
	                            		$friends_dbrows	=	array_slice($friends['email_friends'], 0, $limit);
	                            		$params = CParams::load ();
	                            		$img_webroot_url	=	$params->params->img_webroot_url;
	                            		foreach($friends_dbrows AS $row): 
	                            		$row	=	$row['_source'];
	                            		if($row['have_avatar']){
	                            			$avatar	=	"http://{$img_webroot_url}{$row['avatar']}";
	                            		}else{
	                            			$avatar	=	$row['avatar'];
	                            		}
	                            		$url = Yii::app()->createUrl('//my/view', array('alias' => $row['alias_name']));
	                            		
	                            		$sexualityLabel	=	ProfileSettingsConst::getSexualityLabel();
	                            	?>
                            		<li>
                                                    <img class="left" src="<?php echo $avatar; ?>" align="absmiddle" width="50px" height="50px" />
                                                    <div class="left">
                                                        <p><a href="<?php echo $url; ?>" target="_blank"><b><?php echo $row['username']; ?></b></a></p>
                                                        <p><?php echo Lang::t('settings', 'Sexuality'); ?>: <?php echo $sexualityLabel[$row['sexuality']]; ?></p>
                                                        <p><?php echo Lang::t('settings', 'Role'); ?>: <?php echo $row['sex_role_name']; ?></p>
                                                    </div>
                                                    <div class="right add_friend"><a href="<?php echo $url; ?>" target="_blank"><?php echo Lang::t('friend', 'Add'); ?></a></div>
                                    </li>
                            
                            <?php 
                    			endforeach;
                    		?>
                    		</ul>
                    		<?php
                    			}else{ ?>
	                            <span><?php echo Lang::t('invitation', 'Some friends not found!'); ?></span>
	                        <?php } ?>
                    	</div>
                    </div>
                    <div class="left invite_join">
                    	<h3><?php echo Lang::t('invitation', 'Invite friends to join PLUN?'); ?></h3>
                        <div class="list">
                        	<?php if(isset($contacts[0])){ ?>
                        	<ul>
	                        	<?php foreach($contacts AS $contact): ?>
	                        		<?php if(!empty($contact)):?>
	                            	<li>
	                                   	<p class="left mar_top_10"><?php echo $contact; ?></p>
	                                	<p class="right">
	                                		<?php if(!in_array($contact, $invitation_sent)){ ?>
	                                			<a href="javascript:void(0);" class="btnInvite but_invite" email="<?php echo $contact; ?>"><?php echo Lang::t('invitation', 'Invite'); ?></a>
	                                		<?php }else{ ?>
	                                			<a href="javascript:void(0);" class="btnInvite but_invite"><?php echo Lang::t('invitation', 'Invited'); ?></a>
											<?php } ?>	 
	                                	</p>
	                                </li>  
	                                <?php endif; ?>                    	
	                        	<?php endforeach; ?>                                 
                            </ul>
                            <?php }else{ ?>
                            	<span><?php echo Lang::t('invitation', 'Some contacts not found!'); ?></span>
                             <?php } ?>
                    	</div>
                    </div>