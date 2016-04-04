<div id="headertop">
	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array(	'label'=>'Users & Systems', 
						'url'=>array('//user/user/admin'), 
						'active'=> (VHelper::activeMenu('user')) ? false : false,
						'items'=>array(
								array('label'=>'Users' , 'url'=>array('//user/user/admin')),
								array('label'=>'Translate' , 'url'=>array('//TranslatePhpMessage/translate')),
								array('label'=>'Config' , 'url'=>array('//global/default/config')),
						),
				),
				array(	'label'=>'Website', 
						'url'=>array('/'), 
						'active'=> (VHelper::activeMenu('user')) ? false : false,
						'items'=>array(
								array('label'=>'HotBox' , 'url'=>array('//hotbox/hotbox/admin')),
								array('label'=>'ISU' , 'url'=>array('//systems/notes/admin')),
								array('label'=>'Photo' , 'url'=>array('//systems/photo/admin')),
								array('label'=>'Venues' , 'url'=>array('//venues/venues/admin')),
								array('label'=>'Background' , 'url'=>array('//background/default/config')),
								array('label'=>'Banner' , 'url'=>array('//banner/sysBanner/admin')),
								array('label'=>'V2 Feedback' , 'url'=>array('//systems/V2Feedback')),
						),
				),
				array(	'label'=>'Events', 
						'url'=>array('/'),
						'active'=> (VHelper::activeMenu('user')) ? false : false,
						'items'=>array(
								array('label'=>'Purple Guy' , 'url'=>array('//purpleguy/purpleguyPhoto/admin')),
								array('label'=>'Coupons' , 'url'=>array('//coupon/events/admin')),
						),
				),
				array('label'=>'Login', 'url'=>array('/user/auth'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(!Yii::app()->user->isGuest): ?>
	<div id="topuser">
		Welcome <a rel="userMenus" class="anchorclass" href="#"><strong><?php echo Yii::app()->user->name; ?></strong></a>
        <span>|</span> <?php echo CHtml::link("Log out", array('/user/user/logout'))?>
   	</div>
    <?php endif; ?> 
</div>
<style>
/* drop down list */
#mainmenu ul { list-style: none; margin: 0; padding: 0; position: relative; height: 30px; }
#mainmenu ul li { display: block; float: left; overflow: visible; }
#mainmenu ul li span{color: #FFFFFF;font-size: 12px;font-weight: bold;padding: 5px 8px;display: block;float: left;}
#mainmenu ul li:hover > ul { display: block; }
#mainmenu ul li a { float: left; display: block; }
#mainmenu ul li ul { display: none; position: absolute; top: 70%;background: #165d8d; color: #fff; height: auto;border-radius: 7px;}
#mainmenu ul li ul li a { color: #ccc; padding: 4px 14px; display: block; }		
</style>