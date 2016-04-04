<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PLUN.ASIA</title>
</head>

<body>
<div style="margin:0 auto;">
<table width="" border="0" cellspacing="0" cellpadding="0" style="width:600px; margin:0 auto; background:url(<?php echo Yii::app()->createAbsoluteUrl('/themes/plun1/resources/html/email/images/bg_mail.jpg'); ?>) repeat #dfdfdf; font-family:Arial; font-size:12px; color:#787878;">
  <tr>
    <td valign="middle" align="center">
    	<table width="563" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:12px; color:#565656;">
          <tr style="height:85px;">
            <td style="border-bottom:5px solid #662d91;" valign="middle" align="center"><a href="#"><img src="<?php echo Yii::app()->createAbsoluteUrl('/themes/plun1/resources/html/email/images/logo.png');?>" alt="" align="absmiddle" /></a></td>
          </tr>
          <tr style="height:305px;">
            <td style="border-top:2px solid #d7d7d7; background:#FFF; padding:20px;" align="left" valign="top">
            	<p style="font-size:14px; margin-bottom: 10px;">Hi <b><?php echo $user->getDisplayName();?></b>,</p>
                <p>A new account has been created at <a href="<?php echo Yii::app()->createAbsoluteUrl('/');?>" style="font-weight:bold; text-decoration:none; color:#565656;">www.plun.asia</a> using this e-mail address.</p>
                <p>To validate your e-mail address, you must click the button below </p>                
                <p style="text-align:center; margin:20px 0;"><a style="background:#662c92; padding:10px; color:#FFF; font-size:14px; font-weight:bold; text-decoration:none; text-transform:uppercase;" href="<?php echo $activation_url;?>">Activate account</a></p>
                <p style="width:540px; word-wrap:break-word;">You can also copy and paste the following code: <?php echo $activation_url;?></p>
                              
                <p style="padding: 10px 0;">If your e-mail address is not validated with in 24 hours it will be erased automatically. If that happens, simply use the re-activate function on our system to request new activation email.</p>
                
                <p style="margin-top: 10px;">Regards</p>
				<p style="margin-top: 10px;"><b>PLUN.ASIA</b></p>
            </td>
          </tr>
          <tr>
            <td>
            	<?php require_once Yii::getPathOfAlias('themes.plun1.views.layouts.email.en').'/_footer.php';?>
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>

</div>
</body>
</html>