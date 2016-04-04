<?php
/* @var $this NotesController */
/* @var $model Notes */

$this->breadcrumbs=array(
	'Notes'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Notes', 'url'=>array('index')),
	array('label'=>'Create Notes', 'url'=>array('create')),
	array('label'=>'Update Notes', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Notes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Notes', 'url'=>array('admin')),
);
?>

<h1>View Notes #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'title',
		array(
			'name'=>'body',
			'type'=>'html',
		),
		'image',
		/* array(
			'name' => 'image',
			'type'=>'html',
			'value'=> $model->getImageThumb(array("width" => 60, "heght" => 60)),
		), */
		'privacy_id',
		'status',
		'modify',
		'created',
	),
)); ?>
