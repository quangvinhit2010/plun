<?php
/* @var $this BonusController */
/* @var $model InviteBonus */

$this->breadcrumbs=array(
	'Invite Bonuses'=>array('index'),
	'Manage',
);

?>
<h1>Manage Invite Bonuses</h1>
<?php 
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'album-grid',
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
			'name' => 'history_invited_id',
			'value' => '(!empty($data->historyInvited->inviter)) ? $data->historyInvited->inviter->username : ""',
		),
		array(
			'header' => 'Invited',
			'value' => '(!empty($data->historyInvited->invited)) ? $data->historyInvited->invited->username : ""',
		),
		array(
			'header' => 'Invited Realcash (VND)',
			'name' => 'invited_realcash',
			'filter' => false,
			'value' => '(!empty($data->invited_realcash)) ? number_format($data->invited_realcash) : ""',
		),
		array(
			'header' => 'Rate',
			'name' => 'rate',
			'filter' => false,
		),
		array(
			'header' => 'Bonus (VND)',
			'name' => 'bonus',
			'filter' => false,
			'value' => '(!empty($data->bonus)) ? number_format($data->bonus) : ""',
			'htmlOptions'=>array('style'=>'font-weight: bold;'),
		),
		array(
			'name' => 'execute',
			'value' => '(isset($data->execute)) ? InviteModel::model()->getExecuteStatus($data->execute) : ""',
			'filter'=>InviteModel::model()->getExecuteStatus(),
		),
		/*
		array(
			'name' => 'timeline_rate_id',
			'value' => '(!empty($data->timelineRate)) ? $data->timelineRate->title : ""',
			'filter'=>CHtml::listData(InviteTimelineRate::model()->findAll(), 'id', 'title'),
		),
		*/
		array(
			'header' => 'Fromdate',
			'value' => '(!empty($data->fromdate)) ? date("d-m-Y", $data->fromdate) : ""',			
		),
		array(
			'header' => 'Todate',
			'value' => '(!empty($data->todate)) ? date("d-m-Y", $data->todate) : ""',			
		),
		array(
			'header' => 'Created',
			'value' => '(!empty($data->created)) ? date("d-m-Y", $data->created) : ""',			
		),
		array(
			'header' => 'Award date',
			'value' => '(!empty($data->award_date)) ? date("d-m-Y", $data->award_date) : ""',			
		),
		
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
			'buttons'=>array(
				'update'=>array(
                	'visible'=>'($data->execute!=1)?true:false;'                         	
				),
               ),
		),
	),
)); ?>
