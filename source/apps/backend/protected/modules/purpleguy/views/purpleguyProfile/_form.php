<?php
/* @var $this PurpleguyProfileController */
/* @var $model PurpleguyProfile */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purpleguy-profile-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fullname'); ?>
		<?php echo $form->textField($model,'fullname',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'fullname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	<div class="row listPhotos">
		<?php if(!empty($photos)){?>
		    <ul>
		    <?php foreach ($photos as $photo){?>
		        <?php
		        $folder = 'thumb270x270';
		        if(!empty($photo->name) && file_exists(Yii::getPathOfAlias ( 'pathroot' ) . DS . $photo->path .'/'.$folder.'/'. $photo->name)){
		        $src = Yii::app()->request->getHostInfo(). DS .$photo->path .'/'.$folder.'/'. $photo->name;
		        ?>
                <li>
                    <img alt="" src="<?php echo $src;?>" width="100"/>
                    <div class="control">
                        <div class="listCpanel" photo-id="<?php echo $photo->id;?>" profile-id="<?php echo $model->id;?>">
                            <a href="javascript:void(0)" class="thumb" <?php if($model->thumbnail_id == $photo->id){?>style="display: none;"<?php }?>>Thumb</a>
                            <a href="javascript:void(0)" class="del">Del</a>
                        </div>
                    </div>
                </li>
                <?php }?>		    
		    <?php }?>
		    </ul>
		<?php }?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model, 'status', array('0'=>'Fail', '1'=>'Wait', '2'=>'Pass')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'round_win'); ?>
		<?php echo $form->dropDownList($model, 'round_win', CHtml::listData(PurpleguyRound::model()->findAll(), 'id', 'title'), array('empty' => ' --- Select Round --- ')); ?>
		<?php echo $form->error($model,'round_win'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
		        
<style>
    .listPhotos ul{display: inline-block;}
    .listPhotos ul li{float: left;padding: 5px;list-style-type: none;}
    .listPhotos .control a{font-size: 11px;font-weight: bold;color: white;background: blue;}
    .listPhotos .control .listCpanel{position: absolute; margin-top: -100px;opacity:0.8;margin-left:15px;}
</style>		 

<script type="text/javascript">
$(function(){
	$(".listCpanel .thumb").live("click", function(){
		var _this = $(this);
		$(".listCpanel .thumb").show();
		_this.before('<span class="loading">&nbsp;&nbsp;&nbsp;&nbsp;</span>');
		var phid = $(this).parent().attr('photo-id');
		var prid = $(this).parent().attr('profile-id');
		$.ajax({
		      type: "POST",
		      url: '/admin/purpleguy/purpleguyProfile/thumb',
		      data: {'prid': prid, 'phid': phid},
		      success: function( response ) {
			      $('.loading').remove();
			      _this.hide();
		      }
		});
	});
	
	$(".listCpanel .del").live("click", function(){
		var _this = $(this);
		_this.before('<span class="loading">&nbsp;&nbsp;&nbsp;&nbsp;</span>');
		var phid = $(this).parent().attr('photo-id');
		var prid = $(this).parent().attr('profile-id');
	    var liparent = $(this).closest('li');	    
		$.ajax({
		      type: "POST",
		      url: '/admin/purpleguy/purpleguyProfile/delPhoto',
		      data: {'prid': prid, 'phid': phid},
		      success: function( response ) {
			      $('.loading').remove();
			      liparent.remove();
		      }
		});
	});
});
</script>