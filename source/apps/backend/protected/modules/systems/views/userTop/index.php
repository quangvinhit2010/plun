<?php
/* @var $this UserTopController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Tops',
);

$this->menu=array(
	array('label'=>'Create UserTop', 'url'=>array('create')),
	array('label'=>'Manage UserTop', 'url'=>array('admin')),
);
?>

<h1>User Tops</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
