<?php
/* @var $this HobbiesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Sys Hobbies',
);

$this->menu=array(
	array('label'=>'Create SysHobbies', 'url'=>array('create')),
	array('label'=>'Manage SysHobbies', 'url'=>array('admin')),
);
?>

<h1>Sys Hobbies</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
