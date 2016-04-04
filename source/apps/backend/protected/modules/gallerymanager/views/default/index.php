<?php $this->pageTitle=Yii::app()->name; 
?>
<a href="<?php echo Yii::app()->createUrl('//gallerymanager/default/create')?>">Create Album</a>
<?
if(!empty($galleries)){
	foreach ($galleries as $gallery){
		$photos = $gallery->galleryPhotos;
		if($photos){
		?>
		<div class="photo">
			<?php echo CHtml::link(CHtml::image($photos[0]->getPreview(), '', array('width'=> '100')), Yii::app()->createUrl('//gallerymanager/default/detail', array('id'=>$gallery->id)))?>
			<span><?php echo CHtml::link($gallery->name, Yii::app()->createUrl('//gallerymanager/default/detail', array('id'=>$gallery->id)))?></span>
		</div>
		<?php 
		}else{
		?>
		<div class="photo">
			<span><?php echo CHtml::link($gallery->name, Yii::app()->createUrl('//gallerymanager/default/detail', array('id'=>$gallery->id)))?></span>
		</div>
		<?php 
		}
	}
}
?>
