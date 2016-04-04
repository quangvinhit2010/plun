<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */

$this->breadcrumbs=array(
	'Sys Banners'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Manage Banner', 'url'=>array('admin')),
	array('label'=>'Create Banner', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sys-banner-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Banners</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button', 'style'=>'display: none;')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<div style="font-weight: bolder; margin-top: 20px;">If there are multiple banners are enabled. It will be shown random</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sys-banner-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'image',
			'filter' => false,
			'value' => '$data->getImage()',
			'type'=>'html',
		),
		'url',
		array(
			'name' => 'status',
			'filter' => SysBanner::model()->mapStatus(),
			'value' => '$data->mapStatus()',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
