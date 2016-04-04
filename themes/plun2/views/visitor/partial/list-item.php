<ul class="clearfix uf_show">
	<?php if($totalAll > 0):?>
	<?php $this->renderPartial('partial/item', array(
			'limit'=>$limit,					
			'vstUserViewUser'=>$vstUserViewUser));?>
			
	<?php else :?>
		<li class="empty_photo_list">			
			<p>
				<?php echo Lang::t('visitor', 'No one has viewed your profile recently. Please update your photos and profile to attract attention.')?>
				<a href="<?php echo Yii::app()->createUrl('//my/view', array('alias' => $this->usercurrent->username))?>"><?php echo Lang::t('general', 'Click here')?></a>			
			</p>
		</li>		
	<?php endif;?>		
</ul>
<?php if(!$usercurrentView->isLimitRightToViewVisitor()){?>
<div class="pagging">
	<a data-total="<?php echo $totalAll;?>" data-url="<?php echo $this->usercurrent->createUrl('//visitor/more', array('offset' => $limit))?>" href="javascript:void(0);"><ins></ins></a>
</div>
<?php }?>
<?php 
if(!empty(CParams::load()->params->visitor->more_will_charge) && $usercurrentView->isLimitRightToViewVisitor()){
	$this->renderPartial('partial/more', array(
				'totalAll'=>$totalAll,					
				'nextMore'=>$nextMore,					
				'limit'=>$limit,					
				'vstUserViewUser'=>$vstUserViewUser));
}
?>