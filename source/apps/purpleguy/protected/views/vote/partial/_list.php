<?php
	$_GET['page'] = Yii::app()->request->getQuery('page', '1') + 1;
	$order_by = Yii::app()->request->getQuery('order_by', 'total_vote');
?>
<ul data-top="<?php echo implode(',', $topVoteUsers); ?>">            
	<?php foreach($purpleguy_list as $purpleguy): ?>
	<?php
		$is_top = false;
		
		if(in_array($purpleguy['user_id'], $topVoteUsers))
			$is_top = true;
	?>
	<?php if(!empty($purpleguy->photo)):?>
	<li id="user-<?php echo $purpleguy['user_id'] ?>"<?php if($is_top) echo ' class="top_vote_li"'?>>
		<a href="<?php echo Yii::app()->createUrl('vote/view', array('uid'=>$purpleguy['user_id'], 'username'=>$purpleguy->username));?>" data-url="<?php echo Yii::app()->createUrl('/vote/LoadDetail', array('user_id'=>$purpleguy['user_id'],)) ?>" title="" class="ava">
			<img class="pics" src="<?php echo $purpleguy->photo->getPath() ?>" alt="" border=""/>
			<span class="ava-bg"></span>
			<div class="unhover_animate name<?php if($is_top) echo ' top_vote'?>">
				<?php if($is_top): ?><p><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/icon_top_vote.png" align="absmiddle"></p><?php endif; ?>
				<span class="fname"><?php echo $purpleguy['fullname'] ?></span>
				<div class="more">
					<div class="like_comment">
						<span class="like"><?php echo $purpleguy->totalVote ?></span> <span class="comment"><?php echo $purpleguy->totalComment ?></span>
					</div>
				</div>
			</div>
		</a>
		<?php if(!empty($this->usercurrent)){?>
		<div class="un_function">
			<?php if($purpleguy->checkVoted($this->usercurrent->id, $currentRoundId)): ?>
			<a class="vote vote_active" title="Vote" href="javascript:;"><span>vote</span></a>
			<?php else: ?>
			<a class="vote vote_link" title="Vote" href="<?php echo Yii::app()->createUrl('/vote/voteFor', array('user_id'=>$purpleguy['user_id'])) ?>"><span>vote</span></a>
			<?php endif; ?>
		</div>
		<?php }?>
		<div class="mask"></div>
	</li>
	<?php endif;?>
	<?php endforeach; ?>
</ul>
<?php if(count($purpleguy_list) == Yii::app()->params->purpleguy['limit_per_page']): ?>
<div class="more-wrap">
	<?php $_GET['t'] = time(); ?>
	<a href="<?php echo Yii::app()->createUrl('/vote/index', $_GET) ?>" class="showmore">Show more</a>
</div>
<?php endif; ?>