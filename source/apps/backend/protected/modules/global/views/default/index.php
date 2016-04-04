<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Chat Admin</h1>
<form method="post" name="loginForm" action="<?php echo Yii::app()->params['XMPP']['login_url'];?>">
	<input type="hidden" value="true" name="login">
	<input type="text" value="admin" id="u01" maxlength="50" size="15" name="username">
	<input type="password" id="p01" size="15" name="password" value="<?php echo isset($user) ? $user->password : '' ;?>">
	<input type="submit" value="&nbsp; Login &nbsp;">
</form>
