<div id="this_isu" style="display: none;">
	<li class="item isu-load" id="isu-<?php echo $isu->id;?>">
		<div class="headtitle">
			<a rel="<?php echo $isu->id;?>" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $isu->id));?>"><?php echo $isu->title;?></a>
			<p class="des"><?php echo Lang::t('isu', 'About');?>: <?php echo $isu->date;?> |<br/> <?php echo $isu->location;?></p>
			<div class="arrow"></div>
			
		</div>
		<div class="cont">
			<?php if(isset($isu->image)) { ?>
			<p class="image">
				<a href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $isu->id));?>" rel="<?php echo $isu->id;?>">
					<?php echo $isu->getImageThumb(array('alt' => $isu->title, 'border' => ''));?>
				</a>
			</p>
			<?php } ?>	
			<div class="des"><?php echo Util::partString($isu->body, 0, 200);?></div>
		</div>
		<div class="author">
			<a href="<?php echo $isu->user->getUserUrl();?>" title="" class="ava"><img src="<?php echo $isu->user->getAvatar(); ?>" alt="" border=""/></a>
			<div class="info">
				<p class="name"><span><?php echo $isu->user->getUserLink(array('target' => '_blank'));?></span></p>
				<span class="date"><?php echo Util::getElapsedTime($isu->created);?></span>
			</div>
		</div>
	</li>
</div>