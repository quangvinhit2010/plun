<?php
/* @var $this PurpleguyRoundController */
/* @var $model PurpleguyRound */

$this->breadcrumbs=array(
	'Rounds'=>Yii::app()->createUrl('//table42/Table42DatingRequest/admin'),
	'Rounds'=>array('admin'),
	'Manage',
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#purpleguy-round-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Couples</h1>
<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'purpleguy-round-grid',
	'dataProvider'=>$model->win(),
	'filter'=>$model,
	'columns'=>array(
		array(
				'type' => 'raw',
				'header' => 'ID',
				'value'=> '$data->id',
		),	
		array(
				'type' => 'raw',
				'header' => 'inviter name',
				'value'=> '$data->inviter->username',
		),	
		array(
				'type' => 'raw',
				'header' => 'inviter thumbnail',
				'value'=> '$data->inviter->photo->getImageThumb203x204(false, array("width" => "100px", "height" => "100px"))',
		),	
		array(
				'type' => 'raw',
				'header' => 'friend name',
				'value'=> '$data->friend->username',
		),
		array(
				'type' => 'raw',
				'header' => 'friend thumbnail',
				'value'=> '$data->friend->photo->getImageThumb203x204(false, array("width" => "100px", "height" => "100px"))',
		),
		array(
				'type' => 'raw',
				'name'	=>	'round_id',
				'header' => 'Round',
				'filter' => Table42Round::model()->getList(),	
				'value'=> '$data->round->title',
		),
		array(
				'type' => 'raw',
				'name'	=>	'is_win',
				'header' => 'win',
				'value'=> '$data->is_win	?	"Yes" : "No"',
		),
		array(
				'name'	=>	'vote_total',
				'header' => 'Total vote',
				'value'=> '$data->vote_total',
		),
		array(
				'class'=>'CButtonColumn',
				'template'=>'{update}',
		),
	),
)); ?>
