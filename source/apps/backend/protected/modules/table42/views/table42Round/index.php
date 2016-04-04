<?php
/* @var $this PurpleguyRoundController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Purpleguy Rounds',
);

?>

<h1>Table42 Rounds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
