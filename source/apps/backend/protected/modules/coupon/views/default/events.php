<?php 
$this->pageTitle=Yii::app()->name;
$slug = new SlugBehavior();
?>
<?php if(count($events) > 0) : ?>
	<div class="event-content">
		<div class="title">Chương trình mã quà tặng</div>
	</div>
	<?php foreach ($events as $key => $event): ?>
		<div class="homeleft">		
			<a href="<?php echo Yii::app()->createUrl('//coupon/default/view', array('id' => $event->id, 'slug'=>$slug->getSlug($event->title))); ?>" class="linkhome">
				<img src="<?php echo Yii::app()->theme->baseUrl;  ?>/resources/images/id/iconCode.jpg" />
				<p><span class="hometitle"><?php echo $event->title;?></span><?php echo $event->description;?></p> 
			</a>           
		</div>	
	<?php endforeach; ?>
<?php endif; ?>
<br/>
<br/>
<br/>
<br/>
<a href="javascript:sprPopAjax('<?php echo Yii::app()->createUrl('//coupon/default/getGiftCB', array('id'=>1, 'slug'=>$slug->getSlug('Teaser Game')))?>');" style="position: relative; z-index: 100;" > Đăng Ký </a>