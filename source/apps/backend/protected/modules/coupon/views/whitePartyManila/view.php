<?php
/* @var $this WhitePartyManilaController */
/* @var $model WhitePartyManila */

$this->breadcrumbs=array(
	'White Party Manilas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WhitePartyManila', 'url'=>array('index')),
	array('label'=>'Create WhitePartyManila', 'url'=>array('create')),
	array('label'=>'Update WhitePartyManila', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WhitePartyManila', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WhitePartyManila', 'url'=>array('admin')),
);
?>

<h1>View WhitePartyManila #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'full_name',
		'phone',
		'id_no',
		'createtime',
		'ip',
	),
)); ?>
