<?php
/* @var $this PurpleguyPhotoController */
/* @var $model PurpleguyPhoto */

$this->breadcrumbs=array(
	'Purpleguy Photos'=>array('index'),
	'Create',
);

?>

<h1>Create PurpleguyPhoto</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>