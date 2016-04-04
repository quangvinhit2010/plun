<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/purpleguy/home.js');
Yii::app()->clientScript->registerScript('joinPurpleguy', "Home.joinPurpleguy();", CClientScript::POS_END);
$login = 0;  
if(!Yii::app()->user->isGuest):
    $login = 1;
endif;
setcookie("PHPSESSID", "", time() - 6400);
?>
  <div class="home-left">
    <div class="wrap" id ="triquiback"> <img id="triquibackimg" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/img-purple-guy.jpg" />
      <div class="content-wrap">
        <h2>ai sẽ đoạt được danh hiệu</h2>
        <p><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/html/css/images/purplu_guy.png" align="absmiddle"></p>
        <p><a class="thamgiangay<?php if(!$login) echo ' login-link'?>" href="<?php echo Yii::app()->createUrl('/vote/index', array("#"=>'thamgia'));?>">tham gia ngay</a></p>
      </div>
    </div>
  </div>
  <!-- home left -->
  <div class="home-right scroll_avatar_home">
    <div class="wrap">
      <h3><?php echo $purpleGuyRound->round_name ?></h3>
      <ul>
      	<?php foreach($purpleGuyProfiles as $u): ?>
      	<?php if(!empty($u->photo)):?>
      	<li style="width: 37px;">
      		<a href="<?php echo Yii::app()->createUrl('vote/view', array('uid'=>$u->user_id, 'username'=>$u->username));?>" title="" class="ava">
      			<img src="<?php echo $u->photo->getPath() ?>" alt="" border=""> 
      			<span class="ava-bg"></span>
              	<div class="name"><span class="fname"><?php echo $u['fullname'] ?></span></div>
			</a>
        </li>
        <?php endif;?>
      	<?php endforeach; ?>
      </ul>
      <div class="func_list">
        <ul>
          <li><a class="active but_binhchon<?php if(!$login) echo ' login-link'?>" href="<?php echo Yii::app()->createUrl('/vote/index');?>">bình chọn</a></li>
          <li><a href="<?php echo Yii::app()->createUrl('/vote/index', array("#"=>'thele'));?>" class="but_thele">thể lệ và giải thưởng</a></li>
          <li><a href="<?php echo Yii::app()->createUrl('/vote/index', array("#"=>'hotro'));?>" class="but_hotro">hỗ trợ</a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- home right --> 
<?php
$must_login = Yii::app ()->session->get('must_login'); 
if($must_login == true){
Yii::app ()->session->remove ( 'must_login' );
?>
<script type="text/javascript">
$(function(){
	$('.login-link').trigger( 'click' );
});
</script>
<?php }?>
