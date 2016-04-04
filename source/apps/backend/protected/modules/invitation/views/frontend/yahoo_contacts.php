<?php 
	$i = 0;
	foreach($contacts AS $contact): 
		if($i >= $offset && $i < $offset + $limit && $i < $total){
	?>
	<li>
		<div class="w220 left"><?php echo $contact; ?></div> 
		<div class="w60 right">
	                                			<?php if(!in_array($contact, $invitation_sent)){ ?>
	                                			<a href="javascript:void(0);" class="btnInvite" email="<?php echo $contact; ?>"><?php echo Lang::t('invitation', 'Invite'); ?></a>
	                                			<?php }else{ ?>
	                                			<a href="javascript:void(0);"><?php echo Lang::t('invitation', 'Invited'); ?></a>
												<?php } ?>		
		</div>
	</li>
<?php 
		}
		$i++;
endforeach; ?>