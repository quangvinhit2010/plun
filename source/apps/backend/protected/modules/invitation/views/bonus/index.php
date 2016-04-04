<?php
/* @var $this BonusController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Invite Bonuses',
);

$this->menu=array(
	array('label'=>'Create InviteBonus', 'url'=>array('create')),
	array('label'=>'Manage InviteBonus', 'url'=>array('admin')),
);
?>

<h1>Invite Bonuses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
