<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Send notifications to all users</h1>
<?php if($errorMessage) echo '<p style="color: red">'.$errorMessage.'</p>'; ?>
<h3>Shortcode: <span style="color: rgb(95, 112, 195); font-size: 16px;">{username}</span>, <span style="color: rgb(95, 112, 195); font-size: 16px;">{domain}</span></h3>
<form action="" method="post">
	<?php foreach ($languages as $language): ?>
	<div<?php if($language != 'en' && $language != 'vi' ) echo ' style="display: none;"' ?>>
		<label style="display: block; margin-bottom: 4px;" for="id-<?php echo $language ?>"><?php echo (Yii::App()->locale->getLocaleDisplayName($language)) ? Yii::App()->locale->getLocaleDisplayName($language) : 'Unknown' ?></label>
		<textarea id="id-<?php echo $language ?>" name="<?php echo $language ?>" rows="6" cols="80"><?php if(isset($_POST[$language])) echo htmlentities($_POST[$language]); ?></textarea><br><br>
	</div>
	<?php endforeach; ?>
	<input type="submit" value="Send" style="padding: 6px; width: 80px;">
</form>