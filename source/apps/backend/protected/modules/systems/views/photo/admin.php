<?php
/* @var $this PhotoController */
/* @var $model Photo */

$this->breadcrumbs=array(
	'Photos'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Photo', 'url'=>array('index')),
	array('label'=>'Create Photo', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#photo-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Photos</h1>

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


<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
)); ?>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'photo-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'id'=>'select_photo',
			'class'=>'CCheckBoxColumn',
			'selectableRows' => '50',
		),
	/* 	'id',
		'album_id', */
		array(
			'name' => 'type', 	
			'value' => '$data->getType()',	
			'filter' => false,
		),
		array(
			'name' => 'user_id', 	
			'value' => '$data->user->getDisplayName()',
			//'filter' => true,	
		),
		array(
			'name' => 'name',
			'value' => '$data->getAdminImageThumbnail(false, array("width" => 100))',
			'type'=>'html',
			'filter' => false,
		),
		array(
			'name' => 'created',
			'value' => 'date("d/m/Y - h:i:s", $data->created)',
			'filter' => false,		
		),
		/*
		'title',
		'slug',
		'description',
		'name',
		'path',
		'status',
		'order',
		'created',
		'updated',
		*/
		/* array(
			'class'=>'CButtonColumn',
		), */
	),
)); ?>
<script>
function reloadGrid(data) {
    $.fn.yiiGridView.update('photo-grid');
}
</script>
<?php echo CHtml::ajaxSubmitButton('Delete Selected',array('photo/ajax','action'=>'doDelete'), array('success'=>'reloadGrid')); ?>
<?php echo CHtml::ajaxSubmitButton('Move to Public',array('photo/ajax','action'=>'doMovePublic'), array('success'=>'reloadGrid')); ?>
<?php echo CHtml::ajaxSubmitButton('Move to Vault',array('photo/ajax','action'=>'doMoveVault'), array('success'=>'reloadGrid')); ?>

<?php $this->endWidget(); ?>
