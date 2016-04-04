<?php if((($totalAll - $limit) > 0)):?>
<div class="group_suggestUserVisitor">
	<h4><?php echo Lang::t('visitor', 'And {number} users want to meet you, use your Candy to find out who they are.', array('{number}'=>($totalAll - $limit)));?></h4>
	<div class="btnShowCandyVisitor loadingItem">
		<a href="javascript:;" class="payLimitRightToViewVisitor" data-url="<?php echo $this->usercurrent->createUrl('/candy/purchase');?>"><ins class="icon_common"></ins><?php echo Lang::t('visitor', 'Use Candy Now');?></a>
	</div>
	<div class="userMoreVisitor">
		<?php if(!empty($nextMore)):?>
		<ul>
			<?php for ($i=$limit;$i<($limit + $nextMore);$i++):?>
			<?php 
				$userView = $vstUserViewUser[$i];
				if(!empty($userView->view_id)){

				$Elasticsearch	=	new Elasticsearch();
				$e_user			=	(object)$Elasticsearch->load($userView->view_id);
				
				$params = CParams::load ();
				$img = $params->params->upload_path . DS . $e_user->avatar;
				
				if(file_exists($img)){
					Yii::import('backend.extensions.easyphpthumbnail');
					$thumb = new easyphpthumbnail();
					$thumb -> Thumbsize = 70;
					$thumb -> Pixelate = array(1,15);						
					$avatar = $thumb -> Createbase64($img);
					
				}
				
// 				$avatar = Yii::app()->createUrl('//visitor/thumb', array('p'=>Util::encryptRandCode($e_user->avatar)));
			?>
			<li>
				<span class="imgPixel"><img src="<?php echo $avatar;?>" width="70" /></span>
			</li>
			<?php }?>
			<?php endfor;?>
			<?php if(($totalAll - $limit - $nextMore) > 0):?>
			<li class="showNumMore">
				<div class="c_item_box right">
					<div class="_tempH"></div>
					<div class="c_item loadingItem">
						<a href="javascript:;" class="payLimitRightToViewVisitor coming-soon" data-url="<?php echo $this->usercurrent->createUrl('/candy/purchase');?>"><span><?php echo $totalAll - $limit - $nextMore;?>+ Người</span></a>
					</div>
				</div>
			</li>
			<?php endif;?>
		</ul>
		<?php endif;?>
	</div>
</div>
<?php endif;?>