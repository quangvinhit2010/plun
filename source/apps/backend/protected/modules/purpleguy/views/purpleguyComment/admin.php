<?php
/* @var $this PurpleguyVoteController */
/* @var $model PurpleguyVote */

$this->breadcrumbs=array(
	'Profiles'=>Yii::app()->createUrl('//purpleguy/purpleguyProfile/admin'),
	'Comments'=>Yii::app()->createUrl('//purpleguy/purpleguyComment/admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Profiles', 'url'=>Yii::app()->createUrl('//purpleguy/purpleguyProfile/admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#purpleguy-vote-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Comments</h1>

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
	'id'=>'purpleguy-vote-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'content',
		array(
				'name'=>'username',
				'filter' => false,
				'value'=> '!empty($data->author->username)	?	$data->author->username	:	""'
		),
		array(
				'name'=>'Comment for User',
				'filter' => false,
				'value'=> '!empty($data->item->username)	?	$data->item->username	:	""'
		),			
		array(
				'name'=>'created_date',
				'filter' => false,
				'value'=> '!empty($data->created_date)	?	date("d-m-Y H:i:s", $data->created_date)	:	""'
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
		),
	),
)); ?>
