<?php $page = Yii::app()->request->getParam('page', '1'); ?>
<ul id="hotbox_block" data-sort="hotbox" class="box_masonry">
	<?php
		if($page == 1) {
			$topHotbox = array_shift($hotboxs);
			$this->renderPartial('partial/item', array('hotboxs' => array($topHotbox), 'class'=>'hot items_hot_box', 'originalImage'=>TRUE));
		}
		$this->renderPartial('partial/item', array('hotboxs' => $hotboxs, 'class'=>'items_hot_box', 'originalImage'=>FALSE));
	?>
</ul>
<?php if($pages->pageCount > $page): ?>
	<?php
		$_GET['page'] = $page + 1;
	?>
	<div class="pagging">
		<a href="<?php echo $this->createUrl('/hotbox/index', $_GET) ?>"><ins></ins></a>
	</div>
<?php endif; ?>
