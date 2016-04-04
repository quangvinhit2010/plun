<div class="col-feed col-left">
    <div class="block news-feed news-friends">
        <div class="title">
            <h2><?php echo Lang::t('general', 'Friends'); ?></h2>
        </div>
        <?php if($show_more){ ?>
        	<div class="cont feed-list list-request-addfriend">
        <?php }else{ ?>
        	<div class="cont feed-list list-request-addfriend">
        <?php } ?>
        
        
            <?php if($waiting_request_addfriends['total_request']){ ?>
            <div class="padding"></div>
            <ul class="feed-list-item">
                <?php foreach($waiting_request_addfriends['dbrow'] AS $item): ?>
                <li class="item">
                    <div class="feed clearfix">
                        <a href="<?php echo $item->inviter->getUserUrl();?>" class="ava"><img src="<?php echo $item->inviter->getAvatar(); ?>?t=<?php echo time();?>" alt="" border=""/></a>
                        <div class="info">
                            <p class="text">
                            	<a href="<?php echo $item->inviter->getUserUrl(); ?>">
                                <b>
                                    <?php echo $item->inviter->getDisplayName(); ?>
                                </b>
                                </a>
                                	<?php echo Lang::t('friend', 'Want to add you to friendlist'); ?>
                                    
                            </p>
                            <p class="time-detail">
                                <?php echo date("F j, Y",$item->requesttime); ?> at <?php echo date("h:i A",$item->requesttime); ?>
                            </p>
                            <div class="buttons">
                            	<a class="btn btn-white accept_bt accept_bt_<?php echo $item->inviter_id; ?>" href="javascript:void(0);" onclick="accept_friend('<?php echo $item->inviter_id; ?>', '<?php echo $item->inviter->alias_name; ?>');"><?php echo Lang::t('general', 'Accept'); ?></a>
                                <!-- 
                               	 	<button class="btn btn-white accept_bt_<?php echo $item->inviter_id; ?>" onclick="accept_friend('<?php echo $item->inviter_id; ?>', '<?php echo $item->inviter->alias_name; ?>');"><?php echo Lang::t('general', 'Accept'); ?></button>
                                 -->
                                 <a href="javascript:void(0);" class="btn btn-white decline_bt decline_bt_<?php echo $item->inviter_id; ?>" onclick="decline_friend('<?php echo $item->inviter_id; ?>');"><?php echo Lang::t('general', 'Decline'); ?></a>
                                <!-- 
                                	<button class="btn btn-white  decline_bt_<?php echo $item->inviter_id; ?>" onclick="decline_friend('<?php echo $item->inviter_id; ?>');"><?php echo Lang::t('general', 'Decline'); ?></button>
                                 -->
                            </div>
                        </div>

                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
	        <?php if($show_more): ?>
	        <div class="more-wrap-col2" style="margin-bottom: 32px;">
	           <a class="showmore showmore-request-addfriends" onclick="show_more_request();"><?php echo Lang::t('general', 'Show More'); ?></a>
	        </div>
	        <?php endif; ?>            
            <input type="hidden" name="showmore_offset" id="showmore_offset" value="<?php echo $limit; ?>" />
            <div class="padding"></div>
            <?php }else{ ?>
            <p class="no-request-friends">
            	<?php echo Lang::t('friend', 'No friend requests pending'); ?>
            </p>
            <?php } ?>
        </div>
        <!-- news feed list -->
        
        <input type="hidden" value="<?php echo $limit; ?>" name="showmore_offset" id="showmore_offset_first" /> 


    </div>
    <!-- news feed -->
</div>
<!-- left column -->
<div class="col-right">
    <div class="members">
        <h3>
	        <a href="javascript:void(0);" class="btn-top btn-hide">
	    		<i class="imed imed-arrow-left"></i>
				<span class="text"><?php echo Lang::t('general', 'Hide'); ?></span>
			</a>
	        <a class="btn-top btn-open-feed" href="javascript:void(0);">
		        <span class="text"><?php echo Lang::t('general', 'Show'); ?></span>
		        <i class="imed imed-arrow-right"></i>
	        </a>
	        <p><?php echo sprintf(Lang::t('friend', 'You have %d friends'), $myfriend_list['total_friends']); ?></p>
        </h3>
        <div class="list clearfix">
            <?php if($myfriend_list['total_friends']){ ?>
            <ul>
               <?php 
                 foreach($myfriend_list['dbrow'] AS $item): 
                     if(Yii::app()->user->id == $item->invited->id ) {
                        $item->invited = $item->inviter;
                     }                     
                     if(is_object($item->invited->profile_settings)){
                         $show_more_info    =   true;
                         $sex_role = isset($sex_roles_title[$item->invited->profile_settings->sex_role]) ? $sex_roles_title[$item->invited->profile_settings->sex_role] : '';
                     }else{
                         $show_more_info    =   false;
                         $sex_role  =   '';
                     }
                     
                ?>
                <li class="<?php echo $item->invited->id; ?>">
                    <a href="<?php echo $item->invited->getUserUrl();?>" class="ava">
                        <img src="<?php echo $item->invited->getAvatar(); ?>?t=<?php echo time();?>" alt="" border=""/>
                        <span class="ava-bg"></span>
                        <div class="name">
                            <span class="fname"><?php echo $item->invited->getDisplayName(); ?></span>
                            <?php if($show_more_info): 
                            		$country_name   =   isset($country_info[$item->invited->profile_settings->country_id]['name'])   ?   "{$country_info[$item->invited->profile_settings->country_id]['name']}"    :   '';
                                    $state_name   =   !empty($state_info[$item->invited->profile_settings->state_id]['name'])  ?  "{$state_info[$item->invited->profile_settings->state_id]['name']}, "    :   '' ;                               
                                    $birthday_year   =   isset($item->invited->profile_settings->birthday_year)  ?  $item->invited->profile_settings->birthday_year    :   false ;                               

                             ?>
                            <div class="more">
                                <p class="location">
                                    <span class="text">
                                        <?php echo $state_name; ?><?php echo $country_name; ?>
                                    </span>
                                </p>
                                <?php if($birthday_year): ?>
                                    <p class="contact"><?php echo Lang::t('search', 'Age'); ?>: <?php echo date('Y') - $birthday_year; ?></p>
                                <?php endif; ?>
                                <p class="intro"><?php echo $sex_role; ?></p>    
                            </div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="un_function">
                        <a href="javascript:void(0);" onclick="unfriend('<?php echo $item->invited->id; ?>', '<?php echo $item->invited->getAliasName(); ?>');" title="Unfriend" class="unfriend"></a>
                    </div>
                </li>
                <?php endforeach; ?>              
                <!-- single member photo -->
            </ul>
            <?php }else{ ?>
            <p class="no-friends">
            	<?php echo Lang::t('friend', 'No friend requests pending'); ?>
            </p>
            <?php } ?>         
            <input type="hidden" name="showmore_friendlist_offset" id="showmore_friendlist_offset" value="<?php echo $limit_friendlist; ?>" />
            <!-- members area -->
            <input type="hidden" value="<?php echo $limit; ?>" name="showmore_friendlist_offset" id="showmore_friendlist_offset_first" /> 
            <?php if($show_more_myfriendlist): ?>
            <div class="more-wrap">
                <a class="showmore showmore-friendlist" onclick="showmore_friendlist();"><?php echo Lang::t('general', 'Show More'); ?></a>
            </div>
            <?php endif; ?>
        </div>
        <!-- members list -->
    </div>
    
</div>
<!-- right column -->
