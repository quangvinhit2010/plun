<?php if(!empty($data['data'])){?>
<?php if($data && count($data['data'])>= $config['default'] && !isset($ispagging)) {  
$link = (!empty($data['next'])) ? $this->user->createUrl('/newsFeed/commentsPrevious', array('object_id'=>$object_id, 'page'=>$data['next'])) : '';
?>
<li class="sub-item previous">
	<a href="javascript:void(0);" class="cpagging-comment" rel="<?php echo $link;?>">
		<?php if (!empty($data['pages']->itemCount) && $data['pages']->itemCount > $config['view']) {
		    echo Lang::t('newsfeed', 'View previous comments');
	    }?>
	</a>
</li>
<?php }?>

<?php foreach ($data['data'] as $sitem) {

?>
<li class="sub-item">
	<div class="wrap clearfix">
		<a href="javascript:void(0);" title="" class="sub-ava"><?php echo $sitem->cmt->getAvatar(true);?></a>
		<span class="time"><?php echo Util::getElapsedTime($sitem->created_date) ?></span>
		<div class="sub-info">
			<h5><?php echo $sitem->cmt->getUserLink(array('class' => 'username')); ?></h5>
			<p class="text"><?php echo $sitem->getContent() ?></p>
		</div>
	</div>
</li>
<?php }?>
<?php }?>