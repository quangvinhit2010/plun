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