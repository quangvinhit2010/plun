<?php
?>
<div class="step-search">
    <?php $this->renderPartial('page/_tab');?>
    <div class="step-cont">
    	<h2><?php echo Lang::t('register', 'Suggest Friends')?></h2>
        <p>&nbsp</p>
		<div class="block-step">
        	<div class="block-suggest">
            	<ul class="suggest">
                    <?php foreach ($data as $key => $item): 
						    	$item	=	$item['_source'];
						        $url = Yii::app()->createUrl('//my/view', array('alias' => $item['alias_name']));
                        
                        ?>
                    <li><a target="_blank" href="<?php echo $url; ?>"><img src="<?php echo $item['avatar']; ?>" alt=""/></a><span class="name">
                        <?php echo $item['username']; ?></span>
                        <span class="friend"><?php echo $item['total_friends']; ?> <?php echo Lang::t('register', 'Friends');?></span>
                        <a class="btn btn-white" target="_blank" href="<?php echo $url; ?>" title=""><?php echo Lang::t('register', 'Add friend');?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <a class="back-step" href="<?php echo Yii::app()->createUrl('/register/stepFindFriends');?>" title=""><i></i><?php echo Lang::t('general', 'Back'); ?></a>
	<a class="skip-step" href="<?php echo Yii::app()->user->data()->getUserFeedUrl();?>" title=""><?php echo Lang::t('register', 'Skip this Step'); ?><i></i></a>
</div>