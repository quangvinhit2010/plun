<?php 
foreach ($friends as $item) {
	$exist = InviteHistory::model()->findByAttributes(array('invited_email'=>$item['id'], 'type'=>InviteModel::CONTACT_FACEBOOK));
	if(!empty($item) && empty($exist)){
?>
	<li invited_email="<?php echo $item['id'] ?>" type="<?php echo InviteModel::CONTACT_FACEBOOK;?>">
	<!--	<img alt="<?php echo $item['name'] ?>" src="<?php echo $item['avatar'] ?>">-->
		<?php echo $item['name'] ?>
		<a href="javascript:void(0);" class="invite">Invite</a>
	</li>
<?php 
	}	
}
?>
