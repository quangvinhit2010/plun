<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */

$this->breadcrumbs=array(
	'category'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Manage category', 'url'=>array('admin')),
	array('label'=>'Create category', 'url'=>array('create')),
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

<h1>Manage Venues</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sys-banner-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'title',
			'filter' => false,
			'value' => '$data->title',
			'type'=>'html',
		),
		array(
				'name'=>'published',
				'filter' => false,
				'value'	=>	'$data->published	?	"Yes"	:	"No"'
		),
			array(
				'name' => 'ID',
				'filter' => false,
				'value' => '$data->id',
				'type'=>'html',
			),		
			
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
