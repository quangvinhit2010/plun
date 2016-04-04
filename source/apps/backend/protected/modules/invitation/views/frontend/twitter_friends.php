<?php if(!empty($friends)):?>
<ul class="tw_results" style="display: none;">
<?php 
$i = 0;

foreach ($friends->ids as $key=>$friend_id) {
	$show = $twitter->get("users/show/$friend_id");
	if(!empty($show->id) && !empty($show->name)){
?>
	<li invited_email="<?php echo $show->id ?>" type="<?php echo InviteModel::CONTACT_TWITTER;?>">
		<?php echo $show->name?>
		<a href="javascript:void(0);" class="invite">Invite</a>
	</li>
<?php 
	}	
	if($i == 3){
        break;
    }
    $i++;
}
?>
</ul>
<?php endif;?>
<script type='text/javascript'> 
    var results = $('.tw_results').html(); 
    window.close();
    window.opener.$('#tw_friends .friends').html(results);
    window.opener.$('body').unloading();
    
</script>