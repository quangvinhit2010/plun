<?php
/* @var $this NotesController */
/* @var $model Notes */

$this->breadcrumbs=array(
	'Notes'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Notes', 'url'=>array('index')),
	array('label'=>'Create Notes', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#notes-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Notes</h1>

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
	'id'=>'notes-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'value' => '$data->id',
			'filter' => false,
		),
		/* 'user_id', */
		array(
			'name' => 'user_id',
			'value' => '$data->user->username',
			'filter' => false,
		),
		'title',
		/* 'body', */
		array(
			'name' => 'image',
			'value' => '$data->getImageThumb(array("width" => 60, "heght" => 60))',
			'type'=>'html',
		),
		/* 'privacy_id', */
		array(
			'name' => 'status',
			'value' => '$data->getStatus()',
			'filter'=> CHtml::activeDropDownList($model, 'status', array('' => 'All', Notes::STATUS_ACTIVE => 'Public', Notes::STATUS_PENDING => 'Pending')),
			'type' => 'html',		
		),
		array(
			'name' => 'created',
			'value' => 'date("d/m/Y - h:i:s", $data->created)'		
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
