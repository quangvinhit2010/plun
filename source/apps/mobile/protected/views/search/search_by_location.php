
    <?php
    if($total):
    $params = CParams::load ();
    $img_webroot_url	=	$params->params->img_webroot_url;
    foreach ($data as $key => $item) :
    	$item	=	$item['_source'];
    
        $url = Yii::app()->createUrl('//my/view', array('alias' => $item['alias_name']));
        if($item['have_avatar']){
        	$avatar	=	"http://{$img_webroot_url}{$item['avatar']}";
        }else{
        	$avatar	=	$item['avatar'];
        }
                //$avatar	=	$item['avatar'];
        ?>      
<li>
	<a target="_blank" href="<?php echo $url; ?>"><img src="<?php echo $avatar; ?>" align="absmiddle" alt="<?php echo $item['username']; ?>"></a>
	<div class="info">
		<a target="_blank" href="<?php echo $url; ?>">
	      <?php if(time() - $item['last_activity'] <= Yii::app()->params->Elastic['update_activity_time']){ ?>
                		<label class="is_online"></label>
                	<?php }else{ ?>
                	<label class="is_offline"></label>
        <?php } ?>
		<?php echo $item['username']; ?>
		</a></div>
</li>
      <?php 
      	endforeach; 
      	endif;
      ?> 
