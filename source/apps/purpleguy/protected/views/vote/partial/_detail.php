<?php
    $userCurrent =  Yii::app()->user->data();
    if(empty($purpleGuyProfile->user)){
        throw new CHttpException(403, 'No User');
    }
	$cityInfo = SysCity::model()->getCityInfo($purpleGuyProfile->user->profile_location->current_city_id);
	$countryInfo = SysCountry::model()->getCountryInfo($purpleGuyProfile->user->profile_location->current_country_id);
	$yearsOld = date('Y') - $purpleGuyProfile->user->profile_settings->birthday_year;
	$occupations = ProfileSettingsConst::getOccupationLabel();
	
	$config = PurpleguyComment::ConfigView();
	$type_id = PurpleguyComment::COMMENT_PROFILE;
	$fcomment = PurpleguyComment::model()->getComments($purpleGuyProfile->user_id, $type_id);
?>
<div class="content">
    	<div class="left gallery">        	
            <ul class="bxslider">
            	<?php foreach($purpleGuyProfile->photos as $photo): ?>
            	<li><div class="table"><div class="table-cell"><img class="test" src="<?php echo $photo->getPath('detail1600x900')?>" align="absmiddle"></div></div></li>
            	<?php endforeach; ?>
            </ul>
        </div>
        <div class="right info">
        	<p>MS.<?php echo $purpleGuyProfile->user_id ?></p>
            <p class="fullname"><a target="_blank" href="<?php echo VPurpleguy::model()->createUrl('/u/'.$purpleGuyProfile->user->getAliasName())?>"><?php echo $purpleGuyProfile->fullname ?></a></p>
            <p><b>Nơi ở:</b> <?php echo $cityInfo['name'] ?>, <?php echo $countryInfo['name'] ?></p>
            <p><b>Tuổi:</b> <?php echo $yearsOld ?></p>
            <?php if($purpleGuyProfile->user->profile_settings->occupation): ?>
            <p><b>Công việc:</b> <?php echo $occupations[$purpleGuyProfile->user->profile_settings->occupation] ?>.</p>
            <?php endif; ?>
            <div class="list_comment">
            	<div class="like_comment">
            		<div class="left">
                    	<span class="voting"><?php echo $purpleGuyProfile->totalVote ?></span> <span class="comment"><?php echo!empty($fcomment['pages']->itemCount) ? $fcomment['pages']->itemCount : 0 ?></span>
                	</div>
                	<div class="left share"><b>Share</b> <input onmouseup="return false;" onfocus="this.select();" name="" type="text" value="<?php echo Yii::app()->getBaseUrl(true).'/vote/'.$purpleGuyProfile->user_id.'-'.$purpleGuyProfile->user->username ?>"></div>
                </div>
                <img class="arrow_icon" src="<?php echo Yii::app()->theme->baseUrl ?>/resources/html/css/images/icon_arrow.png" align="top">                
                <ul class="list_item">                    
                    <?php
                    if($fcomment && $fcomment['pages']->itemCount >= $config['default']) {  
                    $link = (!empty($fcomment['next'])) ? $userCurrent->createUrl('/comment/commentsPrevious', array('object_id'=>$purpleGuyProfile->user_id, 'page'=>$fcomment['next'])) : '';
                    ?>
                    <li class="showmore">
                        <a href="javascript:void(0);" class="cpagging-comment" rel="<?php echo $link;?>">
                    		<?php echo Lang::t('general', 'View previous comments');?>
                    	</a>
                    </li>
                    
                    <?php }?>
                    <?php                    
    				if (!empty($fcomment)) {
    					$params['data'] = $fcomment;
    					$params['config'] = $config;
    					$this->renderPartial("//comment/partial/list-comment", $params);
    				}                    
    				?>
				</ul>
    			<ul class="box_comment">	
    				<?php                     
                    $avatar = $userCurrent->getAvatar().'?t='.time();
    				if (!empty($userCurrent)) { ?>
                    <li class="text_comment">
                        <?php
                        $model = new PurpleguyComment();
    					$form = $this->beginWidget('CActiveForm', array(
    						'action' => $userCurrent->createUrl("//comment/post"),
    						'htmlOptions' => array(
    							'class' => 'comment-form'
    						)
    					));
    					?>
                        <?php 
                        ?>
                    	<a class="left" href="#">                    	
                    	    <img src="<?php echo $avatar;?>" width="31" height="31" align="absmiddle">
                    	</a>
                        <div class="left nick_info">
                        	<?php echo $form->textArea($model, 'content', array('class' => 'cmt-post-text', 'placeholder' => 'Write a comment...', 'id'=>'')); ?>
                        	<?php echo $form->hiddenField($model, 'item', array('value' => $purpleGuyProfile->user_id)); ?>
                            <input name="" type="button" class="btnComment" value="Gửi">
                        </div>
                        <?php $this->endWidget(); ?>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="vote_him">
            	<?php if(!empty($voted)): ?>
            	<a class="active" href="javascript:;">
            		<img src="<?php echo Yii::app()->theme->baseUrl ?>/resources/html/css/images/icon_white_heart.png" align="left"> Đã bình chọn
            	</a>
            	<?php else: ?>
            	<a data-id="user-<?php echo $user_id ?>" class="vote_link" href="<?php echo Yii::app()->createUrl('/vote/voteFor', array('user_id'=>Yii::app()->request->getQuery('user_id'))) ?>">
            		<img src="<?php echo Yii::app()->theme->baseUrl ?>/resources/html/css/images/icon_white_heart.png" align="left"> <span>Bình chọn</span>
            	</a>
            	<?php endif; ?>
            </div>
        </div>
    </div>