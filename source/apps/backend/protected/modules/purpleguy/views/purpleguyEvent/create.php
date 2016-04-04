<?php
/* @var $this PurpleguyEventController */
/* @var $model PurpleguyEvent */

$this->breadcrumbs=array(
	'Purpleguy Events'=>array('index'),
	'Create',
);

?>

<h1>Create PurpleguyEvent</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>