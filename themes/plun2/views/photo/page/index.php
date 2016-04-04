<div class="container pheader clearfix hasBanner_160 photos_page wrap_scroll">
	<div class="wrap-feed left">
		<div class="shadow_top"></div>
		<?php CController::forward('/photo/myrequest', false); ?>
	</div>
	<div class="explore left box_margin_fixed">
		<div class="clearfix sticky_column min_height_common wrap_myphotos">
			<?php CController::forward('/photo/listPhotos', false); ?>
			<?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_160)); ?>
		</div>
	</div>
</div>