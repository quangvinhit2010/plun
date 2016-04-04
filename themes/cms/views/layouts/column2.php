<?php $this->beginContent('//layouts/main'); ?>
	<div id="sidebar"><div class="menucontent">
		<?php 
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
		
		?>
	</div></div>
	<div id="maincontent"><?php echo $content; ?></div>
<?php $this->endContent(); ?>