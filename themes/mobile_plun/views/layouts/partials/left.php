<?php 
if(!Yii::app()->user->isGuest){
$urlMy = $this->usercurrent->getUserUrl();
$edit_avatar_url = $this->usercurrent->createUrl('//my/PhotosSetAvatar'); 
$username = $this->usercurrent->getDisplayName();
Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
?>
<div id="box_col_left" class="block_more_info">
  <div class="box_width_common">
    <div class="main_sub">
      <div class="scroll_info_left" >
        <div id="left_panel" data-position="left" data-theme="c">
          <form method="get" class="frm-quicksearch" id="frm-quicksearch" action="<?php echo $this->usercurrent->createUrl('//my/quicksearch');?>">
          <div class="block_search">
            <input type="text" class="input_search" name="q">
            <input type="text" class="icon_menu_plun btn_search" value="" onclick="$('#frm-quicksearch').submit();">
            <div class="clear">&nbsp;</div>
          </div>
          </form>
          <ul class="list_item_panel">
      		<li class="avatar">
      		    <?php 
		        $urlAvatar =  $imageLarge = $this->usercurrent->getNoAvatar();
		        if(!empty($this->usercurrent->avatar)){
		            if(is_numeric($this->usercurrent->avatar)){
		                $photo = Photo::model()->findByAttributes(array('id'=>$this->usercurrent->avatar, 'status'=>1));
		                if(!empty($photo->name) && file_exists(VHelper::model()->path ($photo->path .'/thumb160x160/'. $photo->name))){
		                    $urlAvatar = Yii::app()->createUrl($photo->path .'/thumb160x160/'. $photo->name);
		                    $imageLarge = $photo->getImageLarge(true);
		                }
		            }else{
		                $urlAvatar = $imageLarge = VAvatar::model()->urlAvatar($this->usercurrent->avatar);
		            }
		        }
		        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/avatar.js?t=' .time(), CClientScript::POS_END);
		        ?>
		        <a class="ava" title="" href="javascript:void(0);" lis_me="true" lcaption="" limg="<?php echo $imageLarge . '?t=' . time(); ?>" onclick="Avatar.viewPhotoDetail(this);" lurlphoto="<?php echo $this->usercurrent->createUrl('//photo/index');?>">
	      			<img src="<?php echo $this->usercurrent->getAvatar(); ?>?t=<?php echo time();?>" align="absmiddle">	 
	      		</a>
      			<a href="<?php echo $urlMy;?>" class="username"><?php echo $username;?></a>
      		</li>
            <li><a href="#" data-transition="flip"  class="bg_666 color_fff"><span class="icon_menu_plun_null"></span><?php echo Lang::t('general', 'Favourite')?></a></li>
            <li><a href="#" data-transition="flip" class="coming-soon"> <span class="icon_menu_plun ico_menu_candy">&nbsp;</span><?php echo Lang::t('general', 'Candy')?></a></li>
            <li><a href="<?php echo $this->usercurrent->createUrl('//bookmark/index');?>" data-transition="flip">
            	<span class="icon_menu_plun ico_menu_bookmark">&nbsp;</span><?php echo Lang::t('general', 'Bookmark')?></a>
            </li>
            <li><a href="<?php echo $this->usercurrent->createUrl('//settings/index');?>" data-transition="flip"  ><span class="icon_menu_plun ico_menu_setting">&nbsp;</span><?php echo Lang::t('general', 'Setting')?></a></li>
            <li><a href="#" data-transition="flip"><span class="icon_menu_plun ico_menu_help">&nbsp;</span><?php echo Lang::t('general', 'Help')?></a></li>

            <li><a href=""  class="bg_666 color_fff"><span class="icon_menu_plun_null"></span><?php echo Lang::t('general', 'Language')?></a></li>
            <?php 
    		if(!empty(Yii::app()->language)){
                $langs = SysLanguage::model()->cache(500)->findAllByAttributes(array('enable'=>1));
    		?>
    		    <?php foreach ($langs as $lang){
    		        if($lang->code == 'en'){
                        $class = 'icon_menu_plun ico_menu_english';
                    }elseif($lang->code == 'vi'){
                        $class = 'icon_menu_plun ico_menu_tiengviet';
                    }
                    $clsActive = '';
                    if($lang->code == Yii::app()->language){
                        $clsActive = ' class="active"';
                    }
    		        ?>
                    <li<?php echo $clsActive;?>><a href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>$lang->code));?>" data-transition="flip"><span class="<?php echo $class;?>">&nbsp;</span><?php echo $lang->title;?></a></li>
                <?php }?>
            <?php }?>
            <li class="end"><a href="<?php echo Yii::app()->createUrl('//site/logout')?>"  class="bg_666"><span class="icon_menu_plun ico_menu_logout">&nbsp;</span><?php echo Lang::t('general', 'Log Out')?></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="btn_close_col_left">&nbsp; </div>
<?php 
}
?>