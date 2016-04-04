<?php if(!empty($data['data'])){
$link = (!empty($data['next'])) ?  $this->user->createUrl('/newsFeed/commentsPrevious', array('object_id'=>$object_id, 'page'=>$data['next'])) : '';
?>
<div class="prevData" data-prevLnk="<?php echo $link?>">
<?php 
foreach ($data['data'] as $sitem) {
?>
<li>
	<div class="avatar left"><a href="<?php echo $sitem->author->getUserUrl();?>"><img width="35px" height="35px" src="<?php echo $sitem->cmt->getAvatar(false);?>" /></a></div>
    <div class="left info">
    	<p class="nick"><?php echo $sitem->cmt->getUserLink(array('class' => 'username')); ?> <?php echo $sitem->getContent(); ?></p>
        <p class="time"><?php echo Util::getElapsedTime($sitem->created_date) ?></p>
    </div>
</li>
<?php }?>
</div>
<?php }?>