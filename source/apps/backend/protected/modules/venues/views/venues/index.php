<?php
/* @var $this SysBannerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Venues',
);

$this->menu=array(
	array('label'=>'Create Venues', 'url'=>array('create')),
	array('label'=>'Manage Venues', 'url'=>array('admin')),
);
?>

<h1>Venues</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
