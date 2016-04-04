<?php
/* @var $this HistoryController */
/* @var $model InviteHistory */

$this->breadcrumbs=array(
	'Invite Histories'=>array('index'),
	'Manage',
);

?>

<h1>Manage Invite Histories</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'invite-history-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header' => 'ID',
			'name' => 'id',
			'filter' => false,
		),
		array(
			'header' => 'Inviter',
			'name' => 'user_id',
			'value' => '(!empty($data->inviter)) ? $data->inviter->username : ""',			
		),
		'invited_email',
		array(
			'header' => 'Invited',
			'name' => 'invited_id',
			'value' => '(!empty($data->invited)) ? $data->invited->username : ""',
		),
		array(
			'name' => 'type',
			'value' => '$data->type',
			'filter'=>InviteModel::model()->getTypeContact(),
		),
		array(
			'name' => 'status',
			'value' => '(!empty($data->status)) ? InviteModel::model()->getStatus($data->status) : ""',
			'filter'=>InviteModel::model()->getStatus(),
		),
		array(
			'value' => '(!empty($data->created)) ? date("d-m-Y H:i:s", $data->created) : ""',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
		),
	),
)); ?>
