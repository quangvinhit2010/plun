<div class="alert_link_page" style="display: block;">
<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/alerts/common.js');
if(!empty($data)):
$params = json_decode($data->params, true);
$model = new Comment();
$object_id = $data->id;
$type_id = Comment::COMMENT_ACTIVITY;
	
$config = Comment::ConfigView();
	
/* people comment */
$fcomment = Comment::model()->getComments($object_id, $type_id);
$dataobj = htmlspecialchars(json_encode(array('object_id' => Util::encrypt($object_id), 'action' => Util::encrypt($data->action))));
?>      	   
  <div class="list_status_detail item-active">
          <div class="feed clearfix">
                <a class="ava" title="" href="<?php echo $data->member()->getUserUrl(); ?>"><img width="40" border="" alt="" src="<?php echo $data->member->getAvatar() ?>"></a> 
                <span class="time"><?php echo Util::getElapsedTime($data->timestamp) ?></span>
                <div class="info">
                        <h4><?php echo $data->getHeader(); ?></h4>     
                        <?php if( $data->getMessage()): ?>
						<p class="text">
							<?php echo $data->getMessage(); ?>
						</p>
						<?php endif;?>
                        <div class="nav clearfix">
                        <div class="nav-left">
                        </div>
                        <div class="nav-right">							
							<ol>
								<li>
									<a class="like_comment" href="javascript:void(0);" rel="<?php echo $this->user->createUrl("//newsFeed/like", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"><i class="ismall ismall-like"></i><span class="inline-text"><?php echo $data->getLikeState() ?> (<?php echo $data->getLikeCount() ?>)</span></a>
									<?php if($data->getLikeCount() > 0):?>
									<div class="list_like list_like_down" data-url="<?php echo $this->user->createUrl("//newsFeed/getUserLiked", array('oid' => Util::encrypt($object_id), 'type' => Like::LIKE_ACTIVITY)); ?>"></div>
									<?php endif;?>
								</li>
								<li>
									<a class="btn-comment" href="javascript:void(0);"><i class="ismall ismall-comment"></i><span class="inline-text"><?php echo Lang::t('newsfeed', 'Comment')?> (<?php echo !empty($fcomment['pages']->itemCount) ? $fcomment['pages']->itemCount : 0?>)</span></a>
								</li>
							</ol>
						</div>
                    </div>
                </div>
                <div class="comment">
                    <span class="arrow"><i></i></span>
                    <div class="area">
                        <div class="comment-list">
                            <ul>
								<?php
								if ($fcomment){
									$params['data'] = $fcomment;
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
							<?php if(!empty($this->usercurrent)){?>
							<?php $form=$this->beginWidget('CActiveForm', array(
							    'action' => $this->user->createUrl("//newsFeed/commentActivity"),
							    'htmlOptions' => array(
							        	'class' => 'comment-form'
							        )
							)); ?>
								<div class="input-wrap">
									<?php echo $form->hiddenField($model,'item',array('value'=> $dataobj)); ?>
									<?php echo $form->textArea($model,'content', array('class' => 'cmt-post-text expand', 'placeholder' => Lang::t('newsfeed', 'Write a comment...'))); ?>
								</div>
							<?php $this->endWidget();?>
							<?php }?>
						</div>
                        <!-- write comment -->
                    </div>
                </div>
            </div>
        </div>
<?php endif;?>        
</div>

