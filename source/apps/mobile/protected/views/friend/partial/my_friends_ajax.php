<?php if($myfriend_list['total_friends']){ ?>
	<?php foreach($myfriend_list['dbrow'] AS $item) { ?>
                 <?php $item->invited = (Yii::app()->user->id == $item->invited->id) ? $item->inviter : $item->invited;?>
		<li>
			<a href="<?php echo $item->invited->getUserUrl();?>">
			<img src="<?php echo $item->invited->getAvatar(); ?>?t=<?php echo time();?>" align="absmiddle"/>	
			</a>
			
			<div class="info"><a href="<?php echo $item->invited->getUserUrl();?>"><?php echo $item->invited->getDisplayName(); ?></a></div>
		</li>
	<?php } ?>
<?php } ?>


<?php if($is_show_more) { ?>
	<input type="hidden" id="is_show_more" name="is_show_more" value="1" />
<?php } else { ?>
	<input type="hidden" id="is_show_more" name="is_show_more" value="0" />
<?php } ?>