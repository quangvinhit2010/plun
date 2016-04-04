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

<h1>Manage Table42 Rounds</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'purpleguy-round-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		array(
				'name' => 'time_start',
				'value'=> 'date("d-m-Y",$data->time_start)'
		),
		array(
				'name' => 'time_end',
				'value'=> 'date("d-m-Y",$data->time_end)'
		),	
		array(
				'type' => 'raw',
				'name'	=>	'published',
				'value'=> '$data->published == 1	?	"Yes" : "No"',
		),
		array(
				'type' => 'raw',
				'name'	=>	'disable_vote',
				'value'=> '$data->disable_vote == 1	?	"Yes" : "No"',
		),	
		array(
				'type' => 'raw',
				'name'	=>	'disable_request',
				'value'=> '$data->disable_request == 1	?	"Yes" : "No"',
		),				
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
