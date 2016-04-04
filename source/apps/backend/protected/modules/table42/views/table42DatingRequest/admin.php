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
				'filter' => array('1' => 'Yes', '0' => 'No'),
				'value'=> '$data->is_win	?	"Yes" : "No"',
		),
		array(
				'name'	=>	'vote_total',
				'value'=> '$data->vote_total',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
		),
	),
)); ?>
