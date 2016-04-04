<link rel="stylesheet"
      type="text/css"
      href="<?php echo $this->module->assetsUrl; ?>/main.css"/>

<div id="moduletitle">
    <?php echo $module->id; ?> Module v<?php echo $this->module->version; ?>
</div>

<?php
$langfound = count($module->languages);

switch ($langfound) {
    case 0:
        echo '<div id="modulewarning">';
        echo 'No languages found, you need to create the directory "' . $module->language . '" in:';
        echo '<pre> ' . $module->messagepath . '</pre>';
        echo '</div>';
        break;
    case 1:
        echo '<div id="modulewarning">';
        if ($module->countWhere($module->languages, '==', $module->language) == 1) {
            echo 'No other languages found, create some directories for the other languages in:';
            echo '<pre>' . $module->messagepath . '</pre>';
        } else {
            echo 'Directory for defined language not found, create the directory "' . $module->language . '" in:';
            echo '<pre>' . $module->messagepath . '</pre>';
        }



        echo '</div>';
        break;
    default:
        break;
}
?> 

<div id="modulesettings">
    <h6>
        TranslatePhpMessage module found this settings. You must change them on config file if they are not correct.
    </h6>
    <br/>
    <p>
        Yii default language: <pre><?php echo $module->language ?> (<?php echo $module->languageName($module->language); ?>)</pre><br/>
    <hr/>
    Languages found on <pre><?php echo $module->messagepath ?>:</pre>
    <ul>
        <?php
        foreach ($module->languagesnames as $key => $value) {
            echo '<li>' . $key . ' (' . $value . ')</li>';
        }
        ?>
    </ul>

    <br/>
    <hr/>
    <br/>

    Files found under <pre><?php echo $module->messagepath . '/' . $module->language ?>;</pre>
    <ul>
        <?php
        foreach ($module->fileslist as $value) {
            echo '<li>' . $value . '</li>';
        }
        ?>
    </ul>

</div>

<div id="moduleactions">
    <h5>
        Actions
    </h5>
    <?php if (count($module->fileslist) > 0): ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'action' => Yii::app()->createUrl('TranslatePhpMessage/translate/edit'),
            'method' => 'post',
            'id' => 'translate',
                ));
        ?>

        Translate <?php echo $module->languageName($module->language) ?> to <?php echo CHtml::dropDownList('LanguagesNames', $module->language, $module->languagesnames); ?><br/>

        <?php echo CHtml::submitButton('Translate'); ?>

        <?php $this->endWidget(); ?>

    <?php else: ?>

        No files where found for the defined language.
        Create at least one file containing:
        <pre>
<?php
echo <<<'help'
&lt;?php
    return array()
?&gt;
help;
            ?>
        </pre>


    <?php endif; ?>  

</div>