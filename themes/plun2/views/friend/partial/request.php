    <?php 
    	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/friend.js', CClientScript::POS_END);
    ?>
    <!-- InstanceBeginEditable name="doctitle" -->
    <div class="container pheader min_max_1024 hasBanner_160 fri_rq">
      <div class="explore left">
        <div class="list_explore friend_request_search addColSuggestFriends">
            <div class="shadow_top"></div>
          <div class="friend_request left colAdd">
            <h3><?php echo Lang::t('friend', 'Friend Request'); ?></h3>
            <div class="content">
            	<?php if($waiting_request_addfriends['total_request']){ ?>
              <ul>
              	<?php foreach ($waiting_request_addfriends['dbrow'] AS $item): ?>
                <li>
                  <div class="left info"> 
                  	<a href="<?php echo $item->inviter->getUserUrl();?>" class="left">
                  		<img width="100px" height="100px" src="<?php echo $item->inviter->getAvatar(false); ?>" onerror="$(this).attr('src','/public/images/no-user.jpg');" />
                  	</a>
                    <div class="name">
                      <p class="nick">
                      	<?php if(isset($online_data['online'][$item->inviter->id])){ ?>
							<ins class="status_online"></ins> 
						<?php }else{ ?>
							<ins class="status_offline"></ins> 
						<?php } ?>
                      	
                      	<a href="<?php echo $item->inviter->getUserUrl();?>"><?php echo $item->inviter->getDisplayName(); ?></a></p>
                      <p class="time"><?php echo Util::getElapsedTime($item->requesttime); ?></p>
                    </div>
                  </div>
                  <div class="right addfriendrequest-result-<?php echo $item->inviter_id; ?>">
                  	<a class="active accept-<?php echo $item->inviter_id; ?>" href="javascript:void(0);" onclick="Friend.accept_friend('<?php echo $item->inviter_id; ?>', '<?php echo $item->inviter->alias_name; ?>');"><?php echo Lang::t('general', 'Accept'); ?></a> 
                  	<a href="javascript:void(0);" class="decline-<?php echo $item->inviter_id; ?>"  onclick="Friend.decline_friend('<?php echo $item->inviter_id; ?>');"><?php echo Lang::t('general', 'Decline'); ?></a> 
                  </div>
                </li>
				<?php endforeach;?>
              </ul>
              <?php if($show_more): ?>
              <div class="pagging showmore-request-addfriends">
                <a href="javascript:void(0);" onclick="Friend.show_more_request();"><ins></ins></a>
              </div>
              <?php endif; ?>
              <input type="hidden" name="showmore_offset" id="showmore_offset" value="<?php echo $limit_request; ?>" />
              <input type="hidden" value="<?php echo $limit_request; ?>" name="showmore_offset_first" id="showmore_offset_first" /> 
              <?php }else{ ?>	
              	<p class="no-addfriend-request"><?php echo Lang::t('friend', 'No friend requests pending'); ?></p>
              <?php } ?>
            </div>
          </div>
          <?php $this->widget('frontend.widgets.UserPage.SuggestFriend', array()); ?>
          

        </div>
        <?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_160)); ?>
      </div>
    </div>
    <!-- InstanceEndEditable -->