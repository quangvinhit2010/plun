<?php 
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/photo/photo.js?t=' .time(), CClientScript::POS_END);

$params = json_decode($data->params, true);
$model = new Comment();
$object_id = $data->id;
$type_id = Comment::COMMENT_ACTIVITY;

$config = Comment::ConfigView();

/* people comment */
$fcomment = Comment::model()->getComments($object_id, $type_id);
$dataobj = htmlspecialchars(json_encode(array('object_id' => Util::encrypt($object_id), 'action' => Util::encrypt($data->action))));
?>

<div class="feed clearfix">
	<a href="<?php echo $data->member()->getUserUrl(); ?>" title="" class="ava"><?php echo $data->member->getAvatar(true) ?></a> 
	<span class="time"><?php echo Util::getElapsedTime($data->timestamp) ?></span>
	<div class="info">
		<h4><?php echo $data->getHeader() ?></h4>						
		<?php if ($data->getMessage()): ?>
			<p class="text text_status_<?php echo $data->id;?>"><?php echo $data->getMessage(); ?></p>
			
			<?php if($status_text_is_me): ?>
			<div class="edit_status edit_status_<?php echo $data->id;?>" style="display: none;">
				<textarea rows="3" cols="40"><?php echo $data->getMessage(); ?></textarea>
				<input type="button" name="edit_status" class="edit_status_bt" value="<?php echo Lang::t('general', 'Save');?>"  onclick="NewsFeed.sendEditStatus('<?php echo $data->id; ?>');"/>
				<input type="button" name="cancel_status" class="cancel_status_bt" value="<?php echo Lang::t('general', 'Cancel');?>" onclick="NewsFeed.closeEditStatus('<?php echo $data->id; ?>');" />
			</div>
			<?php endif; ?>
			
			<?php 
			$photos = $data->getObject(4);
			if(!empty($photos)){
			?>
			<ul class="list_avatar photo_request_1">
				<?php foreach ($photos as $photo){?>
		        <li class="list-request-photo-30 item">
		        	<a title="" href="javascript:void(0);" lis_me="true" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);">
		        		<?php echo $photo->getImageThumbnail(false, array('width' => '60', 'height' => '60', 'data-rid' => '30'));?>
	        		</a>
        		</li>
		        <?php }?>
	        </ul>
			<?php 
			}
			?>
		<?php endif; ?>
		<div class="nav clearfix">
			<div class="nav-left">
			    <?php if($data->action == Activity::LOG_PHOTO_UPLOAD){?>
			        <ul style="display: none;">
						<li>
                            <a class="view_photo" href="javascript:void(0);">
                            <i class="ismall ismall-photo"></i>
                            <span class="inline-text"><?php echo Lang::t('newsfeed', 'View Photo');?></span>
                            </a>
                        </li>
					</ul>
			    <?php }?>
			</div>
			<?php if($this->usercurrent->isFriendOf($this->user->id) || $this->user->isMe()){?>
			<div class="nav-right">							
				<ul>
					<li>
						<a href="javascript:void(0);"><i class="ismall ismall-like<?php if(!$data->is_like){?>-unactive<?php }?>"></i><span class="inline-text"><?php echo $data->getLikeCount() ?></span></a>
						<?php if($data->getLikeCount() > 0):?>
						<div class="list_like list_like_down" data-url="<?php echo $this->user->createUrl("//newsFeed/getUserLiked", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"></div>
						<?php endif;?>
					</li>
					<li>
						<a href="javascript:void(0);"><i class="ismall ismall-comment-unactive"></i><span class="inline-text"><?php echo!empty($fcomment['pages']->itemCount) ? $fcomment['pages']->itemCount : 0 ?></span></a>
					</li>
					<li><a class="like_comment" href="javascript:void(0);" rel="<?php echo $this->user->createUrl("//newsFeed/like", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"><span class="inline-text"><?php echo $data->getLikeState() ?></span></a></li>
                    <li><a class="btn-comment" href="javascript:void(0);"><span class="inline-text"><?php echo Lang::t('newsfeed', 'Comment')?></span></a></li>
                    
                    <?php if($status_text_is_me): ?>
                    	<li><a href="javascript:void(0);" onclick="NewsFeed.editStatus('<?php echo $data->id; ?>');"><span class="inline-text"><?php echo Lang::t('newsfeed', 'Edit');?></span></a></li>
                    <?php endif; ?>
                    
                    <?php if($status_text_is_me || $status_photo_is_me): ?>
                    	<li><a href="javascript:void(0);" onclick="NewsFeed.delStatusConfirm('<?php echo $data->id; ?>');"><span class="inline-text"><?php echo Lang::t('newsfeed', 'Delete');?></span></a></li>
                    <?php endif; ?>
				</ul>
			</div>
			<?php }?>
		</div>
	</div>
	<div class="comment">
		<span class="arrow"><i></i></span>
		<div class="area">
			<div class="comment-list">
				<ul>
					<?php
					if ($fcomment) {
						$params['data'] = $fcomment;
						$params['user'] = $this->user;
						$params['usercurrent'] = $this->usercurrent;
						////$params['isLogged'] = $isLogged;
						$params['config'] = $config;
						$params['object_id'] = $object_id;
						$params['type_id'] = $type_id;
						$params['object'] = $dataobj;

						$this->renderPartial("//newsFeed/partial/list-comment", $params);
					}
					?>
				</ul>
			</div>
			<!-- -->
			<div class="comment-input">
				<a href="javascript:void(0);" class="ava">
					<img src="<?php echo $this->usercurrent->getAvatar() ?>" alt="" border=""/>
				</a>
				<?php if (!empty($this->usercurrent)) { ?>
					<?php
					$form = $this->beginWidget('CActiveForm', array(
						'action' => $this->user->createUrl("//newsFeed/commentActivity"),
						'htmlOptions' => array(
							'class' => 'comment-form'
						)
					));
					?>
					<div class="input-wrap">
						<?php echo $form->hiddenField($model, 'item', array('value' => $dataobj)); ?>
						<?php echo $form->textArea($model, 'content', array('class' => 'cmt-post-text expand', 'placeholder' => 'Write a comment...', 'id'=>'')); ?>
					</div>
					<?php $this->endWidget(); ?>
				<?php } ?>
			</div>
			<!-- write comment -->
		</div>
	</div>
</div>