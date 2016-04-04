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

<h1>Manage Table42 Profiles</h1>

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'purpleguy-round-grid',
	'dataProvider'=>$model->approved(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username',	
		'phone',	
		'facebook_id',	
		array(
				'type' => 'raw',
				'name'	=>	'status',
				'value'=> '$data->status == 1	?	"Approved" : ($data->status == 2 ? "Pending" : "DECLINE")',
		),
		array(
				'type' => 'raw',
				'name'	=>	'round_id',
				'header' => 'Round',
				'filter' => Table42Round::model()->getList(),
				'value'=> '$data->round->title',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
