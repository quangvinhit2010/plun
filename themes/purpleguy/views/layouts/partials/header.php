<div class="header">
  <div class="navigation-sec">
    <div class="search left">
      <label class="search_icon"></label>
      <form action="<?php echo $this->createUrl('/vote') ?>"><input name="s" type="text" value="<?php echo Yii::app()->request->getQuery('s') ? Yii::app()->request->getQuery('s'): 'Tìm theo tên hoặc mã số thí sinh' ?>"></form>
    </div>
    <div class="go_plun left"> <a href="<?php echo VPurpleguy::model()->createUrl('/')?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/icon_plun.jpg" align="bottom"></a> <a href="<?php echo VPurpleguy::model()->createUrl('/')?>">
      <label>Quay lại PLUN.ASIA</label>
      </a> </div>
    <?php 
    if(!Yii::app()->user->isGuest):
    $userCurrent =  Yii::app()->user->data();
    $avatar = $userCurrent->getAvatar().'?t='.time();
    ?>      
    <div class="user_online left"> 
        <a class="avatar" href="#"><img src="<?php echo $avatar; ?>" width="20" align="bottom"></a> <a href="#" class="nickname">
          <label><?php echo $userCurrent->getDisplayName();?></label>
        </a>
    </div>
    <div class="user_logout left"> 
        <a href="<?php echo Yii::app()->createUrl('/site/logout');?>" class="nickname">
          <label>Thoát</label>
        </a>
    </div>
    <?php else:?>  
    <div class="user_login left"> 
        <a href="<?php echo Yii::app()->createUrl('/site/logout');?>" class="nickname login-link">
          <label>Đăng nhập</label>
        </a>
    </div>
    <?php endif;?>  
    <?php if(!empty(Yii::app()->language) && false):?>
    <?php
    $arrLang = VLang::model()->getLangs();
    $language = Yii::app()->language;
    // 		    $_lang = Yii::App()->locale->getLocaleDisplayName($language);
    if(!empty($arrLang[$language])){
        $langCls = $arrLang[$language]['class'];
        $langTitle = $arrLang[$language]['title'];
    } 
    ?>
    <div class="navblock nav-country right"> <span class="line"></span> <a href="#" class="btn-country" data-toggle="dropdown"><i class="imed <?php echo $langCls;?>"></i><span class="text"><?php echo $langTitle;?></span><i class="imed imed-arrow-wd"></i></a>
      <div class="spr-submenu">
        <ul>
            <?php foreach ($arrLang as $key=>$item){?>
                <li><a href="<?php echo Yii::app()->createUrl('//site/lang', array('_lang'=>$key))?>"><i class="imed <?php echo $item['class'];?>"></i><span class="text"><?php echo $item['title'];?></span></a></li>
            <?php }?>
        </ul>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>

<?php $this->widget('application.widgets.purpleguy.PopupContent', array('view'=>'login', 'data'=>array())); ?>
<?php $this->widget('application.widgets.purpleguy.PopupContent', array('view'=>'hotro', 'data'=>array())); ?>
<?php $this->widget('application.widgets.purpleguy.PopupContent', array('view'=>'thele', 'data'=>array())); ?>
<?php $this->widget('application.widgets.purpleguy.PopupContent', array('view'=>'vongloai', 'data'=>array())); ?>
<?php if(!Yii::app()->user->isGuest):?>   
    <?php $this->widget('application.widgets.purpleguy.PopupContent', array('view'=>'thamgia', 'data'=>array('userCurrent'=>$userCurrent))); ?>
<?php endif;?>