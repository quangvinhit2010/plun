<?php
/* @var $this TransactionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cr Transactions',
);

$this->menu=array(
	array('label'=>'Create CrTransaction', 'url'=>array('create')),
	array('label'=>'Manage CrTransaction', 'url'=>array('admin')),
);
?>

<h1>Cr Transactions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
