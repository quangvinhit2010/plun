<?php
/* @var $this ReferrerLogController */
/* @var $model ReferrerLog */

$this->breadcrumbs=array(
	'Referrer Logs'=>array('index'),
	'Manage',
);

/* $this->menu=array(
	array('label'=>'List ReferrerLog', 'url'=>array('index')),
	array('label'=>'Create ReferrerLog', 'url'=>array('create')),
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
	$('#referrer-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Referrer Logs</h1>

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
	'id'=>'referrer-log-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		array(
			'name'=>'type_referrer',
			'value'=>'($data->type_referrer == 1) ? "utm_source" : "direct"',
		),
		'redirect_url',
		'referrer_url',
		/* 'referrer_id', */
		array(
				'name'=>'referrer_id',
				'filter' => CHtml::listData(ReferrerDefine::model()->findAll(), 'id', 'domain'),
				'value'=>'($data->define) ? $data->define->domain : null',
		),
		array(
			'name'=>'user_id',
			'filter' => false,
			'value'=>'($data->user_id) ? $data->user->getDisplayName() : null',
		),
		array(
			'name'=>'type_log',
			'filter' => false,
			'value'=>'($data->type_log == 1) ? "Register" : "Login"',
			),
		array(
			'name'=>'created',
			'value'=>'($data->created) ? date("d-m-Y H:i:s", $data->created) : null',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script>
	$(function() {
		$(document).on('focus', '.filters td:eq(7) input', function(){
			$(this).datepicker({dateFormat: 'dd-mm-yy'});
		});
	});
</script>
