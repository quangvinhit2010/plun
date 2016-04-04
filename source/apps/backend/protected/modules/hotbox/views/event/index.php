<?php
/* @var $this EventController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Hotbox Events',
);

$this->menu=array(
	array('label'=>'Create HotboxEvent', 'url'=>array('create')),
	array('label'=>'Manage HotboxEvent', 'url'=>array('admin')),
);
?>

<h1>Hotbox Events</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
