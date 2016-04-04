<?php
/* @var $this SysBannerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Sys Banners',
);

$this->menu=array(
	array('label'=>'Create SysBanner', 'url'=>array('create')),
	array('label'=>'Manage SysBanner', 'url'=>array('admin')),
);
?>

<h1>Sys Banners</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
