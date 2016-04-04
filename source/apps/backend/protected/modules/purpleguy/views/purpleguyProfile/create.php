<?php
/* @var $this PurpleguyProfileController */
/* @var $model PurpleguyProfile */

$this->breadcrumbs=array(
	'Purpleguy Profiles'=>array('index'),
	'Create',
);

?>

<h1>Create PurpleguyProfile</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>