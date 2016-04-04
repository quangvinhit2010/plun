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
            	<p style="font-size:14px;">Dear <b>Ryan vo</b></p>
                <p style="line-height:18px; margin-bottom:0;">The following photo was seen as inappropriate content and removed at 9:52 AM 3/6/2010</p>
				<div style="background:#f3f3f3; text-align:center; padding:10px; margin-top:10px;"><img style="border:1px solid #c9c9c9;" src="<?php echo Yii::app()->createAbsoluteUrl('/themes/plun1/resources/html/email/images/hinh_ketban.jpg');?>" align="absmiddle" /></div>
                <p>Log on now to upload another photo. Set it private or check Community Guidelines to avoid
your photos removed again.</p>
                
                <p>Regards</p>
				<p><b>PLUN.ASIA</b></p>
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
