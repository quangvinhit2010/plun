<?php
/* @var $this PurpleguyProfileController */
/* @var $model PurpleguyProfile */

$this->breadcrumbs=array(
	'Purpleguy Profiles'=>array('index'),
	'Manage',
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#purpleguy-profile-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Purpleguy Profiles</h1>

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
	'id'=>'purpleguy-profile-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username',
		'fullname',
		'phone',
		'email',
		/*
		'thumbnail_id',
		*/
        array(
            'name' => 'status' ,
            'value' => '$data->getStatusName()' ,
            'filter'=>array(0=>'Fail',1=>'Wait',2=>'Pass'),
        ),
		array(
			'class'=>'CButtonColumn',
		),
		array(
				'class'	=>	'CLinkColumn',
				'label'	=>	'Comments',
				'urlExpression'=>'Yii::app()->createUrl("//purpleguy/purpleguyComment/admin", array("PurpleguyComment[item_id]"=>$data["user_id"]))',
		
		)
	),
)); ?>
