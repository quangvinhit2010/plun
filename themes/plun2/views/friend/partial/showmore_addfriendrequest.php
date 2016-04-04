              	<?php foreach ($waiting_request_addfriends['dbrow'] AS $item): ?>
                <li class="item item_showmore" style="display: none;">
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
				<input type="hidden" value="<?php echo $offset; ?>" name="showmore_offset" id="showmore_offset_after" />