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
            	<p style="font-size:14px;">Chào <b><?php echo $user->username;?></b></p>
                <p style="line-height:18px;"><b>Tahoma</b> request to unlock some of your vault photo. Please login to your account and accept (or deny) his request. You can also click to "Accept all" to allow him view all your private photos.</p>
                <table width="" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    	<table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td style="padding-right:10px;"><a href="#"><img src="<?php echo Yii::app()->createAbsoluteUrl('/themes/plun1/resources/html/email/images/photo_private.jpg');?>" align="absmiddle" /></a></td>
                            <td style="padding-right:10px;"><a href="#"><img src="<?php echo Yii::app()->createAbsoluteUrl('/themes/plun1/resources/html/email/images/photo_private.jpg');?>" align="absmiddle" /></a></td>
                            <td style="padding-right:10px;"><a href="#"><img src="<?php echo Yii::app()->createAbsoluteUrl('/themes/plun1/resources/html/email/images/photo_private.jpg');?>" align="absmiddle" /></a></td>
                          </tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
                    <td>
                    	<table border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
                          <tr>
                            <td style="padding-right:10px;"><a href="#" style="background:#662e91; font-size:12px; text-transform:uppercase; color:#FFF; padding:4px 10px; text-decoration:none;">ACCEPT</a></td>
                            <td style="padding-right:10px;"><a href="#" style="background:#662e91; font-size:12px; text-transform:uppercase; color:#FFF; padding:4px 10px; text-decoration:none;">ACCEPT ALL</a></td>
                            <td style="padding-right:10px;"><a href="#" style="background:#dfdfdf; font-size:12px; text-transform:uppercase; color:#333; padding:4px 10px; text-decoration:none;">DECLINE</a></td>
                          </tr>
                        </table>
                    </td>
                  </tr>
                </table>
                
                <p>Regards</p>
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
