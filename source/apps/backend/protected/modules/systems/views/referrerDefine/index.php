<?php
/* @var $this ReferrerDefineController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Referrer Defines',
);

$this->menu=array(
	array('label'=>'Create ReferrerDefine', 'url'=>array('create')),
	array('label'=>'Manage ReferrerDefine', 'url'=>array('admin')),
);
?>

<h1>Referrer Defines</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
