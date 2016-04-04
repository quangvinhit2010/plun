<?php
/* @var $this PurpleguyRoundController */
/* @var $model PurpleguyRound */

$this->breadcrumbs=array(
	'Rounds'=>Yii::app()->createUrl('//table42/table42Round/admin'),
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

<h1>Results</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'purpleguy-round-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',	
		array(
				'type' => 'raw',
				'name'	=>	'round_id',
				'filter' => Table42Round::model()->getList(),
				'value'=> 'isset($data->round->title)	?	$data->round->title : "---"',
		),
		array(
				'type' => 'raw',
				'header' => 'Couple',
				'value'=> '(isset($data->couple->inviter->username) && isset($data->couple->friend->username)) ? $data->couple->inviter->username . " & " . $data->couple->friend->username : "---"',
		),
		array(
				'type' => 'raw',
				'name'	=>	'published',
				'value'=> '$data->published == 1	?	"Yes" : "No"',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
