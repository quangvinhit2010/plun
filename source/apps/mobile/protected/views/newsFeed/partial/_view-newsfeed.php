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

$mediaFilter = new MobileMediaFilter();
?>

<div class="feed clearfix">
	<a href="<?php echo $data->member()->getUserUrl(); ?>" title="" class="ava"><img width="40" src="<?php echo $data->member->getAvatar() ?>" alt="" border="" /></a> 
	<span class="time"><?php echo Util::getElapsedTime($data->timestamp) ?></span>
	<div class="info">
		<h4><?php echo $data->getHeader() ?></h4>						
		<?php if ($data->getMessage()): ?>
			<p class="text">
				<?php echo $mediaFilter->filter(htmlspecialchars($data->getMessage())); ?>
			</p>
			<?php 
			$photos = $data->getObject(3);
			if(!empty($photos)){
			?>
			<ul class="list-photo">
				<?php foreach ($photos as $photo){?>
		        <li class="list-request-photo-30">
		        	<a title="" href="javascript:void(0);" lcaption="<?php echo $photo->description;?>" limg="<?php echo $photo->getImageLarge(true) . '?t=' . time(); ?>" onclick="Photo.viewPhotoDetail(this);">
		        		<img class="w60" align="absmiddle" alt="medium-bba89c09f3a24416ac57980b7c5bce6c-650" src="<?php echo $photo->getImageThumbnail(true);?>" data-rid="30">
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
						<a class="like_comment" href="javascript:void(0);" rel="<?php echo $this->user->createUrl("//newsFeed/like", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"><i class="ismall ismall-like<?php if(!$data->is_like){?>-unactive<?php }?>"></i><span class="inline-text"><?php echo $data->getLikeState() ?> (<?php echo $data->getLikeCount() ?>)</span></a>
						<?php if($data->getLikeCount() > 0):?>
						<div class="list_like list_like_down" data-url="<?php echo $this->user->createUrl("//newsFeed/getUserLiked", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"></div>
						<?php endif;?>
					</li>
					<li>
						<a class="btn-comment" href="javascript:void(0);"><i class="ismall ismall-comment-unactive"></i><span class="inline-text"><?php echo Lang::t('newsfeed', 'Comment')?> (<?php echo!empty($fcomment['pages']->itemCount) ? $fcomment['pages']->itemCount : 0 ?>)</span></a>
					</li>
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
					<img width="40" src="<?php echo $this->usercurrent->getAvatar() ?>" alt="" border=""/>
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