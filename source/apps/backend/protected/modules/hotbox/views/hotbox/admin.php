<?php
/* @var $this HotboxController */
/* @var $model Hotbox */

$this->breadcrumbs=array(
	'Hotboxes'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Hotbox', 'url'=>array('index')),
	array('label'=>'Create Hotbox', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#hotbox-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Hotboxes</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'hotbox-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'value' => '$data->id',
			'filter' => false,
		),
		array(          
            'name'=>'type',
			'filter' => false,
        	'value'=>'($data->type == 1) ? "Event" : "Photo"',
        ),
		'title',
		/*
		'description',
		'body',
		'meta_description',
		'meta_keywords',
		*/
		array(
				'name' => 'author_id',
				'value' => '$data->author->username',
				'filter' => false,
		),
		/* 'thumbnail_id',
		'public_time', */
		array(
				'value' => '$data->view',
				'filter' => false,
		),
		array(
				'name' => 'status',
				'value'=>'$data->getStatus()',
				'filter'=> CHtml::activeDropDownList($model, 'status', array('' => 'All', Hotbox::ACTIVE => 'Public', Hotbox::PENDING => 'Pending')),
				'type' => 'html',
		),
		array(
				'name' => 'created',
				'value' => 'date("d/m/Y - h:i:s", $data->created)',
				'filter' => false,
		),
		/* 'modify', */
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
