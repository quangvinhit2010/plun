<link rel="stylesheet"
      type="text/css"
      href="<?php echo $this->module->assetsUrl; ?>/main.css"/>

<?php
$filename = $_GET['filename'];
$languageid = $_GET['languageid'];
$count=$module->countTranslation($filename, $languageid);
if (
        (file_exists($module->messagepath . '/' . $languageid . '/' . $filename))
        AND
        (file_exists($module->messagepath . '/' . $module->language . '/' . $filename))
) {
    $sourcelanguage = include($module->messagepath . '/' . $module->language . '/' . $filename);
    $targetlanguage = include($module->messagepath . '/' . $languageid . '/' . $filename);
}
else {
    //missing file
    Yii::app()->user->setFlash('error', "File not found, redirected to Edit page".$module->messagepath . '/' . $languageid . '/' . $filename);
    $this->redirect(array(
        'edit',
        'languageid' => $languageid,
    ));
}

if (!is_array($sourcelanguage)) {
    Yii::app()->user->setFlash('error', "Source file error (not an Array), redirected to Edit page");
    $this->redirect(array(
        'edit',
        'languageid' => $languageid,
    ));
}
?>

<div id="moduletitle">
    Editing translation file: <span><?php echo $filename; ?> ( <?php echo $languageid . ' - ' . $module->languagesnames[$languageid]; ?> )</span>

    <a href=<?php echo Yii::app()->createUrl('TranslatePhpMessage/translate/edit', array('languageid' => $languageid)) ?>>choose other file</a>

</div>

<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}
?>


<div id="translationfiles">

    <br/><br/> 
    <table id="translationtable">
        <thead>
            <tr>
                <th colspan="2">Default language: <?php echo $module->language . ' - ' . $module->languagesnames[$module->language] ?></th>
                <th>Target language: <?php echo $languageid . ' - ' . $module->languagesnames[$languageid]; ?></th>
            </tr>
            <tr>
                <th>key</th>
                <th>value</th>
                <th>translation (<?php echo $count[0]>0?$count[0]:'none'; ?> missing)</th>
            </tr>
        </thead>
        <tbody>


            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl('TranslatePhpMessage/translate/savefile'),
                'method' => 'post',
                'id' => 'translate',
                    ));


            foreach ($sourcelanguage as $key => $value) {
                $value == '' ? $sourceclass = 'class="missing"' : $sourceclass = '';
                $value == '' ? $value = 'Default translation is missing!' : '';
                (array_key_exists ($key, $targetlanguage)) ? $targetkey = $targetlanguage[$key] : $targetkey = '' ; 
                
                $targetkey == '' ? $class = 'class="missing"' : $class = '';
                echo '
    <tr>
        <td>' . $key . '</td>
        <td ' . $sourceclass . '>' . $value . '</td>
        <td ' . $class . '>' . CHtml::textArea("translate[$key]", $targetkey, array('cols' => '40', 'rows' => '3'
                )) . '</td>
    </tr>';
            }
            ?>

        </tbody>
    </table>

    <?php
    echo Chtml::hiddenField('file[filename]', $filename);
    echo Chtml::hiddenField('file[languageid]', $languageid);

    echo CHtml::submitButton('Save file');
    $this->endWidget();
    ?>

</div>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl('TranslatePhpMessage/translate/insertline'),
    'method' => 'post',
    'id' => 'insertline',
        ));
?>
<table id="translationinsert">
    <thead>
        <tr>
            <th colspan="2">Insert new line for default language</th>
        </tr>
        <tr>
            <th>key</th>
            <th>value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo CHtml::textField("newline[key]", '', array('size' => '60', 'maxlength' => '128')); ?></td>
            <td><?php echo CHtml::textArea("newline[value]", '', array('cols' => '40', 'rows' => '3')); ?></td>
        </tr>

    </tbody>
</table>

<?php
echo Chtml::hiddenField('newline[filename]', $filename);
echo Chtml::hiddenField('newline[languageid]', $languageid);
echo CHtml::submitButton('Insert new line');
$this->endWidget();
?>