<?php
if(!empty($communities)):
	foreach ($communities as $community):
?>
	<li>
		<a href="<?php echo Yii::app()->createUrl('//communities/view', array('alias'=>$community->community_alias))?>"><?php echo $community->community_name?></a>
	</li>
<?php 
	endforeach;
endif;
?>
