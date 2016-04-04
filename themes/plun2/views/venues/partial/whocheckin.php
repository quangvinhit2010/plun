
<div class="scrollPopup">
	<ul class="clearfix listwhocheckin">
		<?php 
			foreach ($venue_data['data'] AS $row):
		?>
		<li>
			<a class="left" href="<?php echo $row->member->getUserUrl();?>" target="_blank"><img width="50" height="50" src="<?php echo $row->member->getAvatar() ?>"></a>
			<div class="clearfix list_r_u">
				<div class="right c_item_box">
					<div class="_tempH"></div>
					<div class="c_item">
						<a data-id="<?php echo $row->member->username; ?>>" class="icon_common icon_msg_user quick-chat" href="javascript:void(0);"></a>
					</div>
				</div>
				<div class="n_u">
					<div class="c_item_box">
						<div class="_tempH"></div>
						<div class="c_item">
							<a href="<?php echo $row->member->getUserUrl();?>">
							    <?php if(isset($online_data['online'][$row->member->id])){ ?>
                                	<label class="online"></label>
                                <?php }else{ ?>
                                    <label class="offline"></label>
                                <?php } ?>
							<?php echo $row->member->username; ?></a>
						</div>
					</div>
				</div>
			</div>
		</li>
		<?php 
			endforeach;
		?>
	</ul>
	<ul class="clearfix">
		<?php if($venue_data['total'] > $limit): ?>
			<li><a href="javascript:void(0);" class="moreUserLike loadingItem whocheckinshowmore" onclick="Location.showMoreCheckin();"><?php echo Lang::t('venue', 'More'); ?></a></li>
		<?php endif; ?>
	</ul>
	<input type="hidden" name="whocheckin_offset" id="whocheckin_offset" value="<?php echo sizeof($venue_data['data']); ?>">
	<input type="hidden" name="whocheckin_limit" id="whocheckin_limit" value="<?php echo $limit; ?>">
	<input type="hidden" name="whocheckin_venue_id" id="whocheckin_venue_id" value="<?php echo $venue_id; ?>">
	<input type="hidden" name="whocheckin_total" id="whocheckin_total" value="<?php echo $total; ?>">
</div>