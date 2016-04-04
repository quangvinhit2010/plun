<?php if($waiting_request_addfriends['total_request']){ ?>
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
<?php } ?>
<input type="hidden" value="<?php echo $is_show_more; ?>" id="is_show_more" />
<input type="hidden" value="<?php echo $limit_request; ?>" name="showmore_offset" id="showmore_offset_after" />