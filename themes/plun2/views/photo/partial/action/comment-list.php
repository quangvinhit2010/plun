<?php if(!empty($data['data'])){?>
<?php if($data && count($data['data'])>= $config['default'] && !isset($ispagging)) {  
$link = (!empty($data['next'])) ? $this->user->createUrl('/newsFeed/commentsPrevious', array('object_id'=>$object_id, 'page'=>$data['next'], 'view'=>Util::encrypt("//photo/partial/action/comment-previous"))) : '';
?>
<li class="pre_comment">
	<a href="javascript:void(0);" class="cpagging-comment" rel="<?php echo $link;?>">
		<?php if (!empty($data['pages']->itemCount) && $data['pages']->itemCount > $config['view']) {
		    echo Lang::t('newsfeed', 'View previous comments');
	    }?>
	</a>
</li>
<?php }?>

<?php foreach ($data['data'] as $sitem) {

?>
<li>
	<div class="avatar left"><a href="<?php echo $sitem->author->getUserUrl();?>"><img width="35px" height="35px" src="<?php echo $sitem->cmt->getAvatar(false);?>" /></a></div>
    <div class="left info">
    	<p class="nick"><?php echo $sitem->cmt->getUserLink(array('class' => 'username')); ?> <?php echo $sitem->getContent(); ?></p>
        <p class="time"><?php echo Util::getElapsedTime($sitem->created_date) ?></p>
    </div>
</li>
<?php }?>
<?php }?>