<?php
/* @var $this WhitePartyManilaController */
/* @var $model WhitePartyManila */

$this->breadcrumbs=array(
	'White Party Manilas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WhitePartyManila', 'url'=>array('index')),
	array('label'=>'Manage WhitePartyManila', 'url'=>array('admin')),
);
?>

<h1>Create WhitePartyManila</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>