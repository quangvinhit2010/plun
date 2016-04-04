<ul data-sort="isu" id="isu_block">
	<?php foreach($isus as $key => $value): ?>
	<li>
		<div>
			<h3><a class="isu-detail" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $value->id)) ?>"><?php echo $value->title ?></a></h3>
			<p><b><?php echo Lang::t('isu', 'About') ?>:</b> <?php echo date('Y-m-d H:i', $value->date) ?></p>
			<p><b><?php echo Lang::t('isu', 'To') ?>:</b> <?php echo date('Y-m-d H:i', $value->end_date) ?></p>

			<p><b><?php echo Lang::t('isu', 'Location') ?>:</b> <?php echo $value->getLocation() ?></p>
			
			<?php if(is_object($value->venue)): 
           	 	$url	=	Yii::app()->createUrl('venues/getVenueDetail', array('venue_id' => $value->venue->id));
            	?>
            	<p><b><?php echo Lang::t('venue', 'At') ?>:</b> <a class="popupListCheckIn" href="<?php echo $url; ?>"><?php echo $value->venue->title; ?></a></p>
            <?php endif; ?>
			
			<?php if(isset($value->image)): ?>
			<?php $image = $value->getImageThumb(array('alt' => $value->title, 'border' => ''), true) ?>
			<a class="wrap-img-loading isu-detail" data-srcimg="<?php echo $image ?>" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $value->id)) ?>">
				<img align="absmiddle" src="<?php echo $image ?>">
			</a>
			<?php endif; ?>
			<div class="content_post"><p><?php echo strip_tags(Util::partString($value->body, 0, 90)) ?> <a class="isu-detail" href="<?php echo Yii::app()->createUrl('//isu/load', array('id' => $value->id)) ?>">See more</a></p></div>
		</div>
		<div class="poster left">
			<p class="nickname left">
				<a style="width: 20px; height: 20px;" target="_blank" href="<?php echo $value->user->getUserUrl() ?>"><img src="<?php echo $value->user->getAvatar(false) ?>" width="20" height="20" /></a>
				<?php echo $value->user->getUserLink(array('target' => '_blank')) ?>
			</p>
			<p class="time right"><?php echo Util::getElapsedTime($value->created) ?></p>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<div class="clear"></div>
<?php
	$page = Yii::app()->request->getParam('page', '1');
	$_GET['page'] = $page + 1;
?>
<?php if($pages->pageCount > $page): ?>
	<div class="pagging">
		<a href="<?php echo $this->createUrl('/isu/index', $_GET) ?>"><ins></ins></a>
		<div class="clear"></div>
	</div>
<?php endif; ?>
			
			