<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PLUN.ASIA</title>
</head>

<body>
<div style="margin:0 auto;">
<table width="" border="0" cellspacing="0" cellpadding="0" style="width:693px; margin:0 auto; background:url(<?php echo Yii::app()->createAbsoluteUrl('/themes/plun1/resources/html/email/images/bg_mail.jpg');?>) repeat #dfdfdf; font-family:Arial; font-size:12px; color:#787878;">
  <tr>
    <td valign="middle" align="center">
    	<table width="563" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:12px; color:#565656;">
          <tr style="height:85px;">
            <td style="border-bottom:5px solid #662d91;" valign="middle" align="center"><a href="#"><img src="<?php echo Yii::app()->createAbsoluteUrl('/themes/plun1/resources/html/email/images/logo.png');?>" alt="" align="absmiddle" /></a></td>
          </tr>
          <tr style="height:205px;">
            <td style="border-top:2px solid #d7d7d7; background:#FFF; padding:20px;" align="left" valign="top">
            	<p style="font-size:14px;">Chào <b><?php echo $to->getDisplayName();?></b></p>
                <?php if($messages):?>               
                    <?php foreach ($messages as $message):?>     
                        <?php 
                        $msgUser = $message->from_user;
                        $reply = $message->getAnswerLastedToUser(2);
                        $displayMsg = $message;
                        if(!empty($reply)){
                            $displayMsg = $reply;
                        }
                        ?>          
                        <p style="line-height:18px;"><b><?php echo $msgUser->getDisplayName();?></b> vừa gửi cho bạn một tin nhắn cho tiêu đề </b> “<?php echo Util::partString($displayMsg->message, 0, 100);?>” vào lúc <?php echo date('H:i A d/m/Y', $displayMsg->created);?>.</p>
                    <?php endforeach;?>
                <?php endif;?>
                <p style="margin-bottom:2px; margin-top:20px;">Bấm vào đường dẫn để xem thư hoặc trả lời thư: </p>
                <p><a href="#" style="color:#0a5dcf;"><?php echo $to->createAbsoluteUrl('//messages/index');?> </a></p>
                
                <p>Trân trọng</p>
				<p><b>PLUN.ASIA</b></p>
            </td>
          </tr>
          <tr>
            <td>
            	<?php require_once Yii::getPathOfAlias('themes.plun1.views.layouts.email.vi').'/_footer.php';?>
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>

</div>
</body>
</html>
