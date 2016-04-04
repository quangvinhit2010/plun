<?php $this->pageTitle=Yii::app()->name; ?>
<?php 
// $gallery = new Gallery();
// $gallery->name = true;
// $gallery->description = true;
// $gallery->versions = array(
// 		'small' => array(
// 				'resize' => array(200, null),
// 		),
// 		'medium' => array(
// 				'resize' => array(800, null),
// 		)
// );
// $gallery->save();
if(!empty($gallery)){
	$this->widget('application.modules.gallerymanager.widgets.GalleryManager', array(
			'gallery' => $gallery,
			'controllerRoute' => '/gallerymanager/Gallery', //route to gallery controller
	));
}
?>
