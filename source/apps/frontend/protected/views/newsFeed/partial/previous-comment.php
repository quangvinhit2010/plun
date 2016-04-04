<?php if(!empty($data['data'])){
$link = (!empty($data['next'])) ?  $this->user->createUrl('/newsFeed/commentsPrevious', array('object_id'=>$object_id, 'page'=>$data['next'])) : '';
?>
<div class="prevData" data-prevLnk="<?php echo $link?>">
<?php 
foreach ($data['data'] as $sitem) {
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
</div>
<?php }?>