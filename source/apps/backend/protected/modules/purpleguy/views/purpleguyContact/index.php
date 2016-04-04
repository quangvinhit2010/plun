<?php
/* @var $this PurpleguyContactController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Purpleguy Contacts',
);

$this->menu=array(
	array('label'=>'Create PurpleguyContact', 'url'=>array('create')),
	array('label'=>'Manage PurpleguyContact', 'url'=>array('admin')),
);
?>

<h1>Purpleguy Contacts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
