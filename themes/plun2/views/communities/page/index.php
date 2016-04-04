<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/communities.js');
Yii::app()->clientScript->registerScript('CommunitiesInit', "Communities.comInit();", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('CommunitiesListItem', "Communities.comListItem();", CClientScript::POS_END);
?>
<div class="container pheader min_max_1024 clearfix hasBanner_300 alert_page">
	<div class="explore left">
		<div class="list_explore alert_detail">
            <div class="shadow_top"></div>
		<div class="title"><?php echo Lang::t('community', 'Community');?></div>
		<div class="content">
			<div class="tool">
				<h2><a class="createCommunity" href="javascript:void(0);">Create</a></h2>
			</div>
			<ul class="ListCommunities" data-url="<?php echo Yii::app()->createUrl('/communities/listItem');?>" data-type="lasted">
				
			</ul>
			<?php
			$model = new Community(); 
			$this->renderPartial('partial/create', array('model'=> $model));
			?>
			
		</div>
	</div>
	<?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_300)); ?>		
	</div>
</div>

