<?php
/* @var $this WhitePartyManilaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'White Party Manilas',
);

$this->menu=array(
	array('label'=>'Create WhitePartyManila', 'url'=>array('create')),
	array('label'=>'Manage WhitePartyManila', 'url'=>array('admin')),
);
?>

<h1>White Party Manilas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
