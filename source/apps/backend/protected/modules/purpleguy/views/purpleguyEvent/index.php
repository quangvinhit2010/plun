<?php
/* @var $this PurpleguyEventController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Purpleguy Events',
);

?>

<h1>Purpleguy Events</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
