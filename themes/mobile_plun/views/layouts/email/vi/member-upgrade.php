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
          <tr style="height:305px;">
            <td style="border-top:2px solid #d7d7d7; background:#FFF; padding:20px;" align="left" valign="top">
            	<p style="font-size:14px;">Dear <b>Ryan vo</b></p>
                <p>Your membership upgrade/renew request has been received. Below is a summary of your request:</p>
                <p style="margin-bottom:2px; margin-top:20px;"><b>Membership Plan:</b> 1 month</p>
                <p style="margin-top:0; margin-bottom:2px;"><b>Total Due:</b> $5.90</p>
                <p style="margin-top:0;"><b>Payment Method:</b> Cash</p>
                
                <p style="margin-top:20px;">Please visit our office at <b>341 Cao Đạt, Dist.5, HCMC</b> to settle your due. Your request will be actioned immediately</p>
                <p>once we received your payment.</p>
                <p>For further assistance, please call us <i>(08) 1234-5678</i>.</p>
                
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
