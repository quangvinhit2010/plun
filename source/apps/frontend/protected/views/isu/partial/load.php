			<!-- navigation column -->
				<?php $this->renderPartial('partial/filter');?>
				<?php if(isset($isu)) { ?>
				<div class="col-feed col-left">
					<div class="block news-feed hotbox_detail">
						<div class="detail_hotbox">
							<a href="#" class="btn-del-hotbox"></a>
							<h2 style="width:100%;"><?php echo $isu->title;?></h2>
                            <div class="date_location">
	                            <b><?php echo Lang::t('isu', 'Location');?>: <?php echo $isu->getLocation();?></b>
	                            - <b><?php echo Lang::t('isu', 'Date');?>:</b> <?php echo date('Y-m-d H:i', $isu->date);?> - <?php echo date('Y-m-d H:i', $isu->end_date);?> 
                            </div>
                            <?php if($isu->user_id == Yii::app()->user->id && $isu->status == Notes::STATUS_PENDING) {?>
								<div class="public_hotbox"><?php echo Lang::t('isu', 'Your post will be displayed after approved');?></div>
							<?php } ?>
                            <div class="post_by">
                            	<a href="<?php echo $isu->user->getUserUrl();?>" class="avatar_post"><img src="<?php echo $isu->user->getAvatar(); ?>" align="absmiddle" width="24px" height="24px"></a> <b><?php echo $isu->user->getUserLink(array('target' => '_blank'));?></b> <span><?php echo Util::getElapsedTime($isu->created);?></span>
                                <div class="pos_reply_forward">
                                    <ul>
                                    	<?php if($isu->status == Notes::STATUS_ACTIVE) {?>
	                                    	<?php if($isu->user_id != Yii::app()->user->id){?>
	                                        	<li class="reply<?php echo $this->clsAccNotActived;?>"><a href="javascript:ISU.reply();"><?php echo Lang::t('isu', 'Reply');?></a></li>
	                                        <?php } ?>
	                                        <li class="forward<?php echo $this->clsAccNotActived;?>"><a href="javascript:ISU.forward();"><?php echo Lang::t('isu', 'Forward');?></a></li>
	                                    <?php } else { ?>
                                         	<?php if($isu->user_id == Yii::app()->user->id) { ?>
	                                        <li class="edit"><a href="<?php echo Yii::app()->createUrl('/isu/edit', array('id' => $isu->id));?>"><?php echo Lang::t('isu', 'Edit');?></a></li>
	                                        <li class="delete"><a onclick="ISU.delete_isu(<?php echo $isu->id;?>);"><?php echo Lang::t('isu', 'Delete');?></a></li>
                                        	<?php }?>  
                                        <?php }?>  
                                    </ul>
                                </div>
                            </div>
                            <div class="content">
                            	<?php if(isset($isu->image)) { ?>
			    					<p style="text-align: center;">
			    					<?php echo $isu->getImageThumb(array('alt' => $isu->title, 'align' => 'absmiddle', 'style' => 'text-ali'));?>
			    					</p>
		    					<?php } ?>
			    				<?php echo $isu->body;?>
	                            </div>
						</div>						
					</div>
					<?php $this->renderPartial('partial/this_isu', array('isu' => $isu));?>
					<!-- news feed -->
				</div>
				<!-- left column -->
				<?php } ?>
				<?php $this->renderPartial('partial/load_my_isu', array('isu' => $isu, 'my_isus' => $my_isus));?>
				<!-- right column -->
<?php 
$model = new MessageForm();
$model->to = $isu->user->getDisplayName();
if($isu->user_id != Yii::app()->user->id){
	$this->renderPartial('partial/reply', array('model'=>$model));
}
$this->renderPartial('partial/forward', array('model'=>$model, 'isu' => $isu));
?>			