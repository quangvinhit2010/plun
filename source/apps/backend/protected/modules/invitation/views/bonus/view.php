<?php
/* @var $this BonusController */
/* @var $model InviteBonus */

$this->breadcrumbs=array(
	'Invite Bonuses'=>array('index'),
	$model->id,
);
?>

<h1>View InviteBonus #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'history_invited_id',
		'invited_realcash',
		'rate',
		'bonus',
		array(
			'label' => 'Execute',
			'type'=>'raw',
			'value' => (empty($model->execute)) ? 'Not' : 'Done' ,
		),
		'timeline_rate_id',
		array(
			'label' => 'From Date',
			'type'=>'raw',
			'value' => date("d-m-Y", $model->fromdate) ,
		),
		array(
			'label' => 'To Date',
			'type'=>'raw',
			'value' => date("d-m-Y", $model->todate) ,
		),
		array(
			'label' => 'Created',
			'type'=>'raw',
			'value' => date("d-m-Y", $model->created) ,
		),
		array(
			'label' => 'Award date',
			'type'=>'raw',
			'value' => date("d-m-Y", $model->award_date) ,
		),
	),
)); ?>
