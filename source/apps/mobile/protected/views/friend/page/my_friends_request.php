<?php $this->renderPartial('partial/menu');?>
<div class="pad_left_10 pad_top_10">                  
	<div class="left list_friend_request">
		<?php if($waiting_request_addfriends['total_request']){ ?>
			<ul class="feed-list-item">
				<?php foreach($waiting_request_addfriends['dbrow'] AS $item) { ?>
					<li class="item end">
						<div class="feed clearfix">
                        <a href="<?php echo $item->inviter->getUserUrl();?>" class="ava"><img src="<?php echo $item->inviter->getAvatar(); ?>?t=<?php echo time();?>" alt="" border=""/></a>
	                        <div class="info">
	                            <p class="text">
	                            	<a href="<?php echo $item->inviter->getUserUrl(); ?>">
	                            		<b><?php echo $item->inviter->getDisplayName(); ?></b>
	                            	</a><?php echo Lang::t('friend', 'Want to add you to friendlist'); ?>
	                            </p>
	                            <p class="time-detail"><?php echo date("F j, Y",$item->requesttime); ?> at <?php echo date("h:i A",$item->requesttime); ?></p>
	                            <div class="buttons">
	                            	<a class="btn btn-white accept_bt accept_bt_<?php echo $item->inviter_id; ?>" href="javascript:void(0);" onclick="accept_friend('<?php echo $item->inviter_id; ?>', '<?php echo $item->inviter->alias_name; ?>');"><?php echo Lang::t('general', 'Accept'); ?></a>
	                                <a href="javascript:void(0);" class="btn btn-white decline_bt decline_bt_<?php echo $item->inviter_id; ?>" onclick="decline_friend('<?php echo $item->inviter_id; ?>');"><?php echo Lang::t('general', 'Decline'); ?></a>
	                            </div>
	                        </div>
                    	</div>
					</li>
				<?php } ?>
			</ul>
			<?php if($show_more_request_addfriend) { ?>
				<input type="hidden" value="<?php echo $limit_request; ?>" id="showmore_offset" name="showmore_offset">
				<input type="hidden" id="showmore_offset_first" name="showmore_offset" value="<?php echo $limit_request; ?>">
				<div class="block_loading showmore-request-addfriends"><a onclick="show_more_request();" href="javascript:void(0);"><span></span></a></div>
	        <?php } ?>   
		<?php }else{ ?>
			<p class="title"><?php echo Lang::t('friend', 'No friend requests pending'); ?></p>
		<?php } ?>
	</div>
</div>
