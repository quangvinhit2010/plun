<?php
/* @var $this PhotoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Hotbox Photos',
);

$this->menu=array(
	array('label'=>'Create HotboxPhoto', 'url'=>array('create')),
	array('label'=>'Manage HotboxPhoto', 'url'=>array('admin')),
);
?>

<h1>Hotbox Photos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
