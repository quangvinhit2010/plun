<?php
/* @var $this ReferrerDefineController */
/* @var $model ReferrerDefine */

$this->breadcrumbs=array(
	'Referrer Defines'=>array('index'),
	'Manage',
);

/* $this->menu=array(
	array('label'=>'List ReferrerDefine', 'url'=>array('index')),
	array('label'=>'Create ReferrerDefine', 'url'=>array('create')),
); */

$this->menu=array(
		array('label'=>'Reporting Tool', 'url'=>array('index')),
		array('label'=>'Referrer Define', 'url'=>array('//systems/referrerDefine/admin')),
		array('label'=>'Referrer Log', 'url'=>array('//systems/referrerLog/admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#referrer-define-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Referrer Defines</h1>

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

<div style="text-align:right;"><?php echo CHtml::link('Create new Define', array('//systems/referrerDefine/create')); ?></div>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'referrer-define-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'domain',
		'register_count',
		/* 'login_count', */
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
