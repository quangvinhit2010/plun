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
          <tr style="height:254px;">
            <td style="border-top:2px solid #d7d7d7; background:#FFF; padding:0px 20px 0px 20px;">
            	<table width="563" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="width:150px;" align="left" valign="top">
                    	<p style="margin-top:0;"><img src="<?php echo $user->getAvatar();?>" align="absmiddle" width="100" height="100" /></p>
                    	<p style="margin-bottom:2px;"><b><?php echo $user->getDisplayName();?></b></p>
                        <p style="margin-top:0;"><?php echo $lblProfile;?></p>                        
                    </td>
                    <td align="left" valign="top">
                        <p style="font-size:14px; margin-top:0">Dear,</p>
                        <p>I set up profile on PLUN, a gay portal where I can find new friends, post pictures, write notes and I want to add you as a friend to share them with you. First, you need to join PLUN! </p>
                        <p>Once you join, you can also create your own profile.</p>
                        <p style="margin-bottom:2px;">Add me there! My username is <b><?php echo $user->getDisplayName();?>.</b></p>
                        <p style="margin-top:2px; margin-bottom:0;">Thanks</p>
                        <p style="margin-top:2px;"><b>PLUN.ASIA</b></p>
                    </td>
                  </tr>
                </table>

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