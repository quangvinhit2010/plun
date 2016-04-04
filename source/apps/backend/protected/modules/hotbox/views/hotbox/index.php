<?php
/* @var $this HotboxController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Hotboxes',
);

$this->menu=array(
	array('label'=>'Create Hotbox', 'url'=>array('create')),
	array('label'=>'Manage Hotbox', 'url'=>array('admin')),
);
?>

<h1>Hotboxes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
