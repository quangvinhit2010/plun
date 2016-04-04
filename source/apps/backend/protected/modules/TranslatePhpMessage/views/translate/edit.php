<link rel="stylesheet"
      type="text/css"
      href="<?php echo $this->module->assetsUrl; ?>/main.css"/>

<?php

$params = array(
            'module' => $module,
            'languageid' => $languageid,
        );

if (!in_array($languageid, $module->languages)){
    Yii::app()->user->setFlash('error', "Language not found, redirected to Main page");
    $this->redirect(array(
        'index',
    ));
}
    

?>

<div id="moduletitle">
    Edit Translations: <span><?php echo $languageid ?> (<?php echo $module->languagesnames[$languageid] ?>)</span>
     <a href=<?php echo Yii::app()->createUrl('TranslatePhpMessage/translate/index') ?>>choose other language</a>
</div>

<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>

<div id="translationfiles">
    <?php echo $this->renderPartial('_translationfiles', $params); ?>
</div>

